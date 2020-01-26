<?php
/**
 * Created by PhpStorm.
 * User: pomazan_rn
 * Date: 05.11.2019
 * Time: 16:04
 */

namespace App\Support\LeadFilter;


use App\Leed;
use App\Support\UserRole\UserRoleRegionManager;
use App\User;
use App\UserBranches;
use App\UserGroups;
use App\UserRm;
use Illuminate\Support\Facades\Auth;

class LeadFilterRender
{
    /**
     * @param $request
     * @return array
     */
    public static function getLeadFilterStep($request)
    {

        $user = Auth::user();
        $value = self::getValueFilterStepOne($user);


        return ['role' => $user->role_id, 'value' => $value];
    }

    public static function getLeadFilterStepTwo($request)
    {
        $user = Auth::user();
        $value = self::getValueFilterStepTwo($request);

        return ['role' => $user->role_id, 'value' => $value];
    }

    public static function getValueFilterStepOne($request)
    {
        if ($request->role_id == 1 || $request->role_id == 5) {
            return UserGroups::getUserGroup();
        } elseif ($request->role_id == 2) {
            return self::getGroupRm($request);
        } elseif ($request->role_id == 4) {
            return UserRm::rmBranches();
        }
    }

    public static function getValueFilterStepTwo($request)
    {
        if (Auth::user()->role_id == 1) {
          return LeadFilterStepRole::analyst($request);
        } elseif (Auth::user()->role_id == 2) {
            return LeadFilterStepRole::chief($request);
        } elseif (Auth::user()->role_id == 4) {
            return LeadFilterStepRole::regionManager($request);
        }elseif ( Auth::user()->role_id == 5){
            return LeadFilterStepRole::callCenterManager($request);
        }
    }


    private static function getGroupRm($request)
    {
        return User::getWorkUser()
            ->where('role_id', 4)
            ->where('group_id', $request->group_id)
            ->get();
    }

    public static function chooseFilterMethod($request)
    {

        if (!empty($request->user_id)){
            return self::getUserFromUsers($request);
        }elseif (!empty($request->salon_id)){
            return self::getUserFromSalons($request);
        }elseif (!empty($request->regionManager_id)){
            return self::getUserFromRegionManager($request);
        }else if (!empty($request->group_id)){
            return self::getUserFromGroup($request);
        }
    }

    public static function getUserFromGroup($request)
    {
        return User::getWorkUser()
            ->where(function ($q) use($request){
                if ($request->group_id == 3){
                    foreach (UserGroups::getUserGroup() as $item){
                        $q->orWhere('group_id', $item->id);
                    }
                }else{
                    $q->orWhere('group_id', $request->group_id);
                }
            })->get();
    }

    public static function getUserFromRegionManager($request)
    {
        return User::getWorkUser()
            ->where(function ($q) use($request){
               foreach (UserRm::getSalonsRegionManagers($request) as $salon){
                   $q->orWhere('branch_id', $salon->id);
               }
            })->get();
    }

    public static function getUserFromSalons($request)
    {
        return User::getWorkUser()
            ->where(function ($q) use($request){
                foreach ($request->salon_id as $salon){
                    $q->orWhere('branch_id', $salon);
                }
            })->get();
    }

    public static function getUserFromUsers($request)
    {
        return User::getWorkUser()
            ->where(function ($q) use($request){
                foreach ($request->user_id as $item){
                    $q->orWhere('id', $item);
                }
            })
            ->get();
    }

}