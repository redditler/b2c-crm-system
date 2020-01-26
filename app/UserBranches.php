<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Support\LeadFilter\LeadFilterRender;
use App\User;
use App\UserRegions;

class UserBranches extends Model
{

    public function groups()
    {
        return $this->belongsTo('App\UserGroups', 'group_id', 'id');
    }

    public function monthlyPlans()
    {
        return $this->hasMany('App\MonthlyPlan', 'branch_id', 'id');
    }
    public function dailyReports()
    {
        return $this->belongsTo('App\DailyReport', 'branch_id', 'id');
    }

    public static function getBranches(array $regionsId = [])
    {
        $reg_id = [];
        if(empty($regionsId)) {
            $user = Auth::user();
            foreach ($user->regions as $region) {
                $reg_id[] = $region->region_id;
            }
        }
        else {
            $reg_id = $regionsId;
        }

        return self::where(function($q) use($reg_id) {
            foreach($reg_id as $id) {
                $q->orWhere('region_id', $id);
            }
        })->get();
    }

    public static function getBranchUsersWithConversion($request) {
        $response = [];

        $branch = self::find($request['branchId']);

        $users = User::query()
            ->where('branch_id', $branch->id)
            ->where('role_id', 3)
            ->get();

        foreach($users as $user) {
            $count_done = Leed::countLeadsByUserId([$user['id']], $request, 15);
            $count_all  = Leed::countLeadsByUserId([$user['id']], $request, null);
            $count_rjct = Leed::countLeadsByUserId([$user['id']], $request, null, 1);
            $response[] = [
                'all'           => $count_all,
                'done'          => $count_done,
                'conversion'    => $count_all ? number_format(($count_done/$count_all)*100, 1) : 0,
                'name'          => $user['name'],
                'branch_name'   => $branch->name,
                'rejected'      => $count_rjct
            ];
        }

        return $response;
    }

    public static function getBranchesWithConversion($period = [], $groupId)
    {
        $response = [];

        if($groupId != 1){
            $branches = self::getBranchesByUserRole($period, $groupId);
            foreach($branches as $branch) {
                $response[] = [
                    'id'        => $branch->id,
                    'name'      => $branch->name,
                    'all'       => $branch->cnt_all,
                    'done'      => $branch->cnt_done,
                    'conversion'=> $branch->cnt_all ? number_format(($branch->cnt_done / $branch->cnt_all)*100, 1) : 0,
                    'rejected'  => $branch->cnt_rejected,
                    'channel'   => $branch->group_id
                ];
            }
        }else{
            $getManagers = User::where('role_id', 3)->where('fired', 1)->where('group_id', 1)->get();
            foreach($getManagers as $thisManager){
                $filterManagers[] = $thisManager->id;
                $manResolver[$thisManager['id']] = $thisManager['name'];
            }
            foreach($filterManagers as $currentManager){
                $manSummary = Leed::countLeadsByUserId([$currentManager], $period, null);
                $manDone = Leed::countLeadsByUserId([$currentManager], $period, 15);
                $manRejected = Leed::countLeadsByUserId([$currentManager], $period, null, 1);
                $response[] = [
                    'id'        => $currentManager,
                    'name'      => $manResolver[$currentManager],
                    'all'       => $manSummary,
                    'done'      => $manDone,
                    'conversion'=> $manSummary ? number_format(($manDone / $manSummary)*100, 1) : 0,
                    'rejected'  => $manRejected,
                    'channel'   => 1
                ];
            }
        }
        return $response;
    }

    public static function getRegionalManagersStats($period=[], $groupId=false)
    {

        $getManagers = User::where('role_id', ($groupId == 1 ? 3 : 4))->where('fired', 1)->where('group_id', $groupId)->get()->toArray();
        foreach ($getManagers as $thisRm) {
            $thisRmUsers = [];
            $res = new \stdClass();
            $res->regionManager_id = [$thisRm['id']];
            $nameParts = explode(' ', $thisRm['name']);
            $managersResolver[$thisRm['id']] = $nameParts[0].' '.mb_substr($nameParts[1], 0, 1).'. '.mb_substr($nameParts[2], 0, 1).'.';
            $getRmUsers = LeadFilterRender::getUserFromRegionManager($res);
            if($getRmUsers->count()>0){
                foreach($getRmUsers as $thisRmUser){
                    $thisRmUsers[] = $thisRmUser->id;
                }
            }
            $managersCount[$thisRm['id']] = Leed::countLeadsByUserId(($groupId == 1 ? [$thisRm['id']] : $thisRmUsers), $period, 15);
            unset($res);
        }
        return [
            'resolver'  => $managersResolver,
            'count'     => $managersCount
        ];
    }

    protected static function getBranchesByUserRole($period = [], $groupId = 2)
    {
        $str = '';
        $reg = '';
        $groupJoin = '';
        $groroupWhere = '';
        $reg_id = [];

        $user = Auth::user();

        if($user->role_id == 3) {
            $reg .= ' AND ub.id = ' . $user->branch_id;
            $str .= ' AND users.id = ' . $user->id;
        }
        if($user->role_id == 4) {
            $regions = UserRm::getRegions();
            foreach($regions as $region) {
                $reg_id[] = $region['id'];
            }
            $reg .= ' AND ub.region_id IN (' . implode(',', $reg_id) . ')';
        }
        if($user->role_id == 1) {
            if($groupId == 1 ) {
                $groroupWhere .= ' AND ub.id = 14';
            } else {
                $groupJoin .= ' JOIN users ON (ub.id = users.branch_id) ';
                $groroupWhere .= ' AND users.group_id = ' . $groupId;
            }
        }

        return DB::query()->select(DB::raw('
            ub.id, ub.name, ub.group_id,
            (
                SELECT COUNT(leeds.id) 
                FROM leeds
                JOIN users ON (users.id = leeds.user_id)
                JOIN user_branches ON (users.branch_id = user_branches.id)
                WHERE leeds.rejected_lead = 0
                AND leeds.created_at >= "' . $period['leadDateFrom'] . '"
                AND leeds.created_at <= "' . $period['leadDateTo'] . '"
                AND users.role_id = 3' .
                    $str
                . '
                AND user_branches.id = ub.id
            ) AS cnt_all,
            (
                SELECT COUNT(leeds.id) 
                FROM leeds 
                JOIN users ON (users.id = leeds.user_id)
                JOIN user_branches ON (users.branch_id = user_branches.id)
                WHERE leeds.status_id = 15
                AND leeds.created_at >= "' . $period['leadDateFrom'] . '"
                AND leeds.created_at <= "' . $period['leadDateTo'] . '"
                AND users.role_id = 3 '.
                    $str
                . '
                AND user_branches.id = ub.id
            ) AS cnt_done,
            (
                SELECT COUNT(leeds.id) 
                FROM leeds 
                JOIN users ON (users.id = leeds.user_id)
                JOIN user_branches ON (users.branch_id = user_branches.id)
                WHERE leeds.rejected_lead = 1
                AND (`contact_id`, leeds.id) IN (SELECT `contact_id`, MAX(leeds.id) FROM leeds GROUP BY `contact_id`)
                AND leeds.created_at >= "' . $period['leadDateFrom'] . '"
                AND leeds.created_at <= "' . $period['leadDateTo'] . '"
                AND users.role_id = 3 '.
                    $str
                . '
                AND user_branches.id = ub.id
            ) AS cnt_rejected
            FROM user_branches ub
            '. $groupJoin . ' WHERE 1 ' . $groroupWhere .
            $reg
            . '
            GROUP BY ub.id
        '))->get();
    }

    public function users()
    {
        return $this->hasMany('App\User', 'branch_id', 'id');
    }

    public static function getGroupBranch($var)
    {
        return self::query()
            ->where(function ($q) use ($var) {
                if ($var->groupId != 3)
                    $q->orWhere('group_id', $var->groupId);
            })
            ->get();
    }



    public static function getMonthlyPlanBranches()
    {
        return UserBranches::query()->where(function ($q) {
            foreach (UserGroups::getUserGroup() as $group) {

                $q->orWhere('group_id', $group->id);
            }
        })->get();
    }

    public static function getActiveBranches()
    {
        return self::query()
            ->where('active', 1);
    }

}
