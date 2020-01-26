<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\UserBranches;
use App\Contact;
use App\Leed;
use App\MonthlyPlan;
use App\DailyReport;
use App\User;

use Carbon\Carbon;
use DateTime;
use DateInterval;
use DatePeriod;

class DashboardsController extends Controller
{
    public function index()
    {
                                            # Юрченко                   Лалетин
        if((Auth::user()->role_id == 1) or (Auth::user()->id == 65) or (Auth::user()->id == 158)){
            return view('dashboards.index');
        }else{
            return redirect('leads');
        }
    }

    public function buildMonthlyPlan($since, $till, $groupId)
    {
        /*
            Значения по умолчанию
        */
        $currentPlan = [
            'planned' => [
                'frameworks'    => 0,
                'sum'           => 0
            ],
            'succeed' => [
                'frameworks'    => 0,
                'sum'           => 0
            ],
            'progress' => [
                'frameworks'    => 0,
                'sum'           => 0
            ]
        ];

        /*
            Определение периода выборки
        */
        $start    = (new DateTime(date('Y-m-d', $since)))->modify('first day of this month');
        $end      = (new DateTime(date('Y-m-d', $till)))->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);
        $selectingYM = [];
        foreach ($period as $dt) {
            $selectingYM[] = [
                $dt->format('Y'),
                $dt->format('n')
            ];
        }

        /*
            Определение точек, по которым нужно получить план
        */
        $getChannelBranches = UserBranches::select(['id'])->where('group_id', $groupId)->get();

        /*
            Получение плана по ТРТ
        */
        $getPlan = MonthlyPlan::select(['branch_id', 'frameworks', 'sum'])
            ->whereIn('branch_id', $getChannelBranches)
            ->where('active', 1)
            ->where(function($query) use ($selectingYM) {
                foreach($selectingYM as $thisPeriod){
                    $query->orWhere(function($intQuery) use ($thisPeriod){
                        $intQuery->where('year', $thisPeriod[0])
                            ->where('month', $thisPeriod[1]);
                    });
                }
            })
            ->get();
        if($getPlan->count()>0){
            foreach($getPlan as $thisPlan){
                $currentPlan['planned']['frameworks'] += $thisPlan->frameworks;
                $currentPlan['planned']['sum'] += $thisPlan->sum;
            }
        }

        /*
            Получение показателей выполнения плана за отчетный период
        */
        $getReports = DailyReport::select(['branch_id', 'count_framework_payments', 'common_sum_payments'])
            ->whereIn('branch_id', $getChannelBranches)
            ->where(function($query) use ($selectingYM) {
                foreach($selectingYM as $thisPeriod){
                    $query->orWhere('date', 'like', $thisPeriod[0].'-'.(strlen($thisPeriod[1]) == 1 ? '0' : '').$thisPeriod[1].'-%');
                }
            })
            ->get();
        if($getReports->count()>0){
            foreach($getReports as $thisReport){
                $currentPlan['succeed']['frameworks'] += $thisReport->count_framework_payments;
                $currentPlan['succeed']['sum'] += $thisReport->common_sum_payments;
            }
        }

        $currentPlan['progress']['frameworks'] = $currentPlan['planned']['frameworks']>0 ? round(($currentPlan['succeed']['frameworks']/$currentPlan['planned']['frameworks'])*100, 2) : 0;
        $currentPlan['progress']['sum'] = $currentPlan['planned']['sum']>0 ? round(($currentPlan['succeed']['sum']/$currentPlan['planned']['sum'])*100, 2) : 0;

        return $currentPlan;
    }

    private static function buildLeadsList($request)
    {
        $leadPeriodDate['leadDateFrom'] = isset($request['leadDateFrom']) ? Carbon::make($request['leadDateFrom'] . ' 00:00:00')->format('Y-m-d H:i:s') : $dateFromLead[0];
        $leadPeriodDate['leadDateTo'] = isset($request['leadDateTo']) ? Carbon::make($request['leadDateTo'] . ' 23:59:59')->format('Y-m-d H:i:s') : $dateFromLead[1];
        $leadsList = Leed::leadAllFromDataTables(Auth::user(), $leadPeriodDate, $request, false, [0,1])->get();
        $leedstatReturn = [
            'rejected_count' => 0
        ];
        if($leadsList->count()>0){
            foreach($leadsList as $thisLead){
                $checkContacts[] = $thisLead->contact_id;
            }
            $checkContacts = array_unique($checkContacts);
            $getContacts = Contact::whereIn('id', $checkContacts)->where('group_id', $request['userGroup'])->get();
            foreach($leadsList as $thisLead){
                if(!$getContacts->contains($thisLead->contact_id)){
                    continue;
                }
                if($thisLead->rejected_lead == 1){
                    $leedstatReturn['rejected_count'] += 1;
                }else{
                    if(isset($leedstatReturn['status_'.$thisLead->status_id])){
                        $leedstatReturn['status_'.$thisLead->status_id] += 1;
                    }else{
                        $leedstatReturn['status_'.$thisLead->status_id] = 1;
                    }
                }
            }
        }
        return [$leedstatReturn];
    }

    public function init(Request $request)
    {
        if((Auth::user()->role_id != 1) and (Auth::user()->id != 65) and (Auth::user()->id != 158)){
            return redirect('leads');
        }
        $groupId = $request->has('userGroup') ? $request->get('userGroup') : Auth::user()->group_id;
        if($request->get('branchId')){
            $period = $request->all();
            session(['period' => $request->all()]);
            if($request->session()->has('period')) {
                $period = $request->session()->get('period');
                $period['branchId'] = $request->get('branchId');
            }
            return UserBranches::getBranchUsersWithConversion($period);
        }
        session(['period' => $request->all()]);
        $sessionValues = $request->session()->get('period');

        if($request->buildType == "all"){
            $getBranches = UserBranches::getBranchesWithConversion($request->all(), $groupId);
            if(count($getBranches)>0){
                foreach($getBranches as $branchLine=>$thisBranch){
                    if(($thisBranch['channel'] != Auth::user()->group_id) and (Auth::user()->group_id != 3)){
                        unset($getBranches[$branchLine]);
                    }
                }
            }
            return [
                'branches'  => $getBranches,
                'rmdetails' => UserBranches::getRegionalManagersStats($request->all(), $groupId),
                'qualifiers'=> Contact::getQualifyCount($request->session()->get('period')),
                'sources'   => Contact::getContactsSources($request->session()->get('period')),
                'genders'   => Contact::getContactsGenders($request->session()->get('period')),
                'ages'      => Contact::getContactsAges($request->session()->get('period')),
                'leedstat'  => self::buildLeadsList(
                    [
                        'leadDateTo'    => $sessionValues['leadDateTo'],
                        'leadDateFrom'  => $sessionValues['leadDateFrom'],
                        'userGroup'     => $groupId
                    ]
                ),
                'telephony' => json_decode(file_get_contents('http://172.17.3.40/api/EKlkYkxSDyEo2D61nONo8kVP9w1NT2yE/usage-by-channel?channel='.$groupId.
                        '&beginPeriod='.strtotime($sessionValues['leadDateFrom']).'&endPeriod='.strtotime($sessionValues['leadDateTo']))),
                'plan'      => self::buildMonthlyPlan(strtotime($sessionValues['leadDateFrom']), strtotime($sessionValues['leadDateTo']), $groupId)
            ];
        }elseif($request->buildType == "conversion"){
            $branchesRetn = UserBranches::getBranchesWithConversion($request->all(), $groupId);
            if(count($branchesRetn)>0){
                foreach($branchesRetn as $branchLine=>$thisBranch){
                    if(($thisBranch['channel'] != Auth::user()->group_id) and (Auth::user()->group_id != 3)){
                        unset($branchesRetn[$branchLine]);
                    }
                }
            }
            if(!empty($request->sortType)){
                usort(
                    $branchesRetn,
                    function($first, $second) use ($request) {
                        if($request->sortType == "summary"){
                            return $first['all'] < $second['all'];
                        }elseif($request->sortType == "conversion"){
                            return $first['conversion'] < $second['conversion'];
                        }elseif($request->sortType == "paid"){
                            return $first['done'] < $second['done'];
                        }
                    }
                );
            }
            return [
                'branches'  => $branchesRetn
            ];
        }elseif($request->buildType == "ages"){
            $agesRetn = Contact::getContactsAges($request->session()->get('period'))->toArray();
            if(!empty($request->sortType)){
                usort(
                    $agesRetn,
                    function($first, $second) use ($request) {
                        if($request->sortType == "summary"){
                            return $first['count'] < $second['count'];
                        }elseif($request->sortType == "ages"){
                            return $first['age'] > $second['age'];
                        }
                    }
                );
            }
            return [
                'ages'      => $agesRetn
            ];
        }elseif($request->buildType == "rejected"){
            $branchesRetn = UserBranches::getBranchesWithConversion($request->all(), $groupId);
            if(!empty($request->sortType)){
                usort(
                    $branchesRetn,
                    function($first, $second) use ($request) {
                        if($request->sortType == "main"){
                            return $first['all'] < $second['all'];
                        }elseif($request->sortType == "paid"){
                            return $first['done'] < $second['done'];
                        }elseif($request->sortType == "bounce"){
                            return $first['rejected'] < $second['rejected'];
                        }
                    }
                );
            }
            return [
                'rejected'  => $branchesRetn
            ];
        }
    }

    public function telephonyDetailed(Request $request)
    {
        if((Auth::user()->role_id != 1) and (Auth::user()->id != 65) and (Auth::user()->id != 158)){
            return redirect('leads');
        }
        $branchesAssoc = [];    $rqBranches = [];
        $telFilters = [
            'since'     => false,
            'till'      => false,
            'group'     => false,
            'lines'     => false
        ];

        if($request->session()->has('period')) {
            $sessionFilters = $request->session()->get('period');
        }

        if(!empty($request->leadDateFrom)){
            $telFilters['since'] = $request->leadDateFrom;
        }elseif(isset($sessionFilters['leadDateFrom'])){
            $telFilters['since'] = $sessionFilters['leadDateFrom'];
        }
        if(!empty($request->leadDateTo)){
            $telFilters['till'] = $request->leadDateTo;
        }elseif(isset($sessionFilters['leadDateTo'])){
            $telFilters['till'] = $sessionFilters['leadDateTo'];
        }
        if(!empty($request->group_id)){
            $telFilters['group'] = $request->group_id;
        }elseif(isset($sessionFilters['userGroup'])){
            $telFilters['group'] = $sessionFilters['userGroup'];
        }
        if(!empty($request->report_sort)){
            $telFilters['sort'] = $request->report_sort;
        }else{
            $telFilters['sort'] = 'summary';
        }

        if($telFilters['group'] == 1){
            $getChannelBranches = User::select(['id', 'name'])->where('group_id', $telFilters['group'])->where('fired', 1)->get();
        }else{
            $getChannelBranches = UserBranches::select(['id', 'name'])->where('group_id', $telFilters['group'])->get();
        }
        foreach($getChannelBranches as $thisBranch){
            $branchesAssoc[$thisBranch->id] = $thisBranch->name;
            $rqBranches[] = $thisBranch->id;
        }

        $queryUri = 'http://172.17.3.40/api/USnVSLTcOFaHx91Jr1K7WR1uS8qdygSc'.
            '/usage-by-region?line_id='.implode(',', $rqBranches).'&channel='.($telFilters['group'] == 2 ? 1 : 2).'&beginPeriod='.strtotime($telFilters['since']).'&endPeriod='.strtotime($telFilters['till']).'&sort='.$telFilters['sort'];
        $getUsageData = json_decode(file_get_contents($queryUri));
        return view('dashboards.telephony')
            ->with('branchesResolver',  $branchesAssoc)
            ->with('usageData',         $getUsageData)
            ->with('telFilters',        $telFilters);
    }

    public function branchDetails(Request $request)
    {
        if((Auth::user()->role_id == 1) or (Auth::user()->id == 65) or (Auth::user()->id == 158)){
            $branch_id = $request->route('id');
            return view('dashboards.details', ['branch_id' => $branch_id, 'detailsType' => $request->route('detailsType')]);
        }else{
            return redirect('leads');
        }
    }

    public function detailedGraph($detailsType)
    {
        if((Auth::user()->role_id == 1) or (Auth::user()->id == 65) or (Auth::user()->id == 158)){
            return view('dashboards.detailedGraph')
                ->with('detailsType', $detailsType);
        }else{
            return redirect('leads');
        }
    }

}
