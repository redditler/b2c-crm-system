<?php

namespace App;

use App\Support\UserRole\SelectRole;
use App\Support\UserRole\UserRoleRegionManager;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function MongoDB\BSON\toJSON;

class DailyReport extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'count_in_calls',
        'count_out_calls',
        'count_clients',
        'count_culations',
        'count_framework_culations',
        'common_culations',
        'count_bills',
        'count_framework_bills',
        'common_sum_bills',
        'count_payments',
        'count_framework_payments',
        'common_sum_payments',
        'count_done_leeds',
        'direct_sample',
        'workload',
        'date',
    ];

    public function manager()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function branch()
    {
        return $this->hasOne('App\UserBranches', 'id', 'branch_id');
    }

    public function monthlyPlan()
    {
        return $this->hasMany('App\MonthlyPlan', 'branch_id', 'branch_id');

    }

    public static function statistic($date = false)
    {
        $user = Auth::user();

        if ($user->role_id == 3) {
            return redirect('/');
        }

        if ($user->group_id == 1) {
            $branchAll = UserBranches::where('region_id', '==', 13)->get()->toArray();
            $leedAll = Leed::where('leed_region_id', '==', 13)->get()->toArray();
            $dailyReportAll = DailyReport::where('branch_id', '==', 14)->get()->toArray();
            $monthlyPlanAll = MonthlyPlan::where('branch_id', '==', 14)->get()->toArray();


        } elseif ($user->group_id == 2) {

            if ($user->role_id == 4) {
                $branchAll = UserRm::rmBranches();
                $leedAll = UserRm::rmLeeds();
                $dailyReportAll = UserRm::rmReports();
                $monthlyPlanAll = UserRm::rmPlanBranches();
            } else {
                $branchAll = UserBranches::where('region_id', '!=', 13)->get()->toArray();
                $leedAll = Leed::where('leed_region_id', '!=', 13)->get()->toArray();
                $dailyReportAll = DailyReport::where('branch_id', '!=', 14)->get()->toArray();
                $monthlyPlanAll = MonthlyPlan::where('branch_id', '!=', 14)->get()->toArray();
            }

        } elseif ($user->group_id == 3) {
            $leedAll = Leed::all()->toArray();
            $branchAll = UserBranches::all()->toArray();
            $dailyReportAll = DailyReport::all()->toArray();
            $monthlyPlanAll = MonthlyPlan::all()->toArray();
        }

        $sum = [
            'planConstruct' => 0,//конструкций в плане
            'planSum' => 0,//сумма плана
            'count_clients' => 0,//посетители
            'leedAll' => 0,//всего лидов
            'leedInWork' => 0,//лиды взятые в работу
            'count_done_leeds' => 0,//выполненых лидов
            'count_in_calls' => 0,//звонки входящие
            'count_out_calls' => 0,//звонки исходящие
            'count_lost_calls' => 0,//звонки пропущенные
            'count_culations' => 0,//количество просчетов
            'common_culations' => 0,//сумма просчетов
            'direct_sample' => 0,//количество замеров
            'count_framework_culations' => 0,//количество конструкция в просчете
            'count_bills' => 0,//выставленно счетов
            'count_framework_bills' => 0,//количество конструкций по счетам
            'common_sum_bills' => 0,//сумма по счетам
            'count_payments' => 0,//количество оплаченых счетов
            'count_framework_payments' => 0,//количество оплаченых конструкций
            'common_sum_payments' => 0,//сумма оплат
        ];


        $dateFrom = $date ? $date['dateFrom'] : Carbon::make(Carbon::now()->format('Y-m') . '-01')->format('Y-m-d');
        $dateTo = $date ? $date['dateTo'] : Carbon::now()->format('Y-m-d');
        $period = CarbonPeriod::create($dateFrom, $dateTo)->toArray();

        $days = [];
        $tableBranches = [];
        $tableDays = [];


        $tableBranches['sum'] = $sum;
        $tableDays['sum'] = $sum;

        $branches = [];
        $branchRegions = [];
        $newBranches = [];
        foreach ($branchAll as $branch) {
            $branches[$branch['id']] = $branch;
            $branchRegions[$branch['region_id']][$branch['id']] = 0;
            $newBranches[] = $branch;
        }

        foreach ($period as $val) {
            $days[$val->format('Y-m-d')] = $sum;
            foreach ($branches as $branch) {
                $tableDays[$val->format('Y-m-d')][$branch['id']] = $sum;
            }

            $tableDays[$val->format('Y-m-d')]['sum'] = $sum;
        }

        foreach ($branches as $branch) {
            $tableBranches[$branch['id']] = $days;
            $tableBranches[$branch['id']]['sum'] = $sum;
        }

        $dailyReports = [];
        foreach ($dailyReportAll as $report) {
            if (Carbon::make($report['created_at'])->format('Y-m-d') >= $dateFrom && Carbon::make($report['created_at'])->format('Y-m-d') <= $dateTo) {
                $dailyReports[] = $report;
            }
        }

        $leeds = [];
        $newLeeds = [];
        foreach ($leedAll as $key => $leed) {
            if (Carbon::make($leed['created_at'])->format('Y-m-d') >= $dateFrom && Carbon::make($leed['created_at'])->format('Y-m-d') <= $dateTo) {
                foreach ($branchRegions as $k => $branch) {
                    foreach ($branch as $n => $val) {
                        if ($k == $leed['leed_region_id']) {
                            $leeds[$n][Carbon::make($leed['created_at'])->format('Y-m-d')][] = $leed;
                            $leeds[$n]['sum'][] = $leed;
                        }
                    }
                }
                $newLeeds[] = $leed;
            }
        }

        $monthlyPlans = [];
        $testPlan = [];
        foreach ($monthlyPlanAll as $plan) {
            if (Carbon::make('01-' . $plan['month'] . '-' . $plan['year'])->format('Y-m') >= substr($dateFrom, 0, -3) && Carbon::make(Carbon::now()->format('d') . '-' . $plan['month'] . '-' . $plan['year'])->format('Y-m') <= substr($dateTo, 0, -3)) {
                $monthlyPlans[$plan['branch_id']][] = $plan;
                $testPlan[$plan['branch_id']][Carbon::make($plan['year'] . '-' . $plan['month'])->format('Y-m')] = $plan;
            }
        }

        foreach ($tableBranches as $key => $branch) {
            foreach ($monthlyPlans as $br_id => $arr) {
                foreach ($arr as $plan) {
                    if ($key == 'sum') {
                        $tableBranches['sum']['planConstruct'] += $plan['frameworks'];
                        $tableBranches['sum']['planSum'] += $plan['sum'];
                    }
                }
                if ($key == $br_id) {
                    foreach ($arr as $plan) {
                        $tableBranches[$key]['sum']['planConstruct'] += $plan['frameworks'];
                        $tableBranches[$key]['sum']['planSum'] += $plan['sum'];
                    }
                }
            }
        }


        foreach ($tableBranches as $key => $branch) {
            foreach ($dailyReports as $report) {
                if ($key == 'sum') {
                    $tableBranches['sum']['count_clients'] += !is_null($report['count_clients']) ? $report['count_clients'] : 0;
                    $tableBranches['sum']['leedInWork'] += !is_null($report['count_done_leeds']) ? $report['count_done_leeds'] : 0;
                    $tableBranches['sum']['count_in_calls'] += !is_null($report['count_in_calls']) ? $report['count_in_calls'] : 0;
                    $tableBranches['sum']['count_out_calls'] += !is_null($report['count_out_calls']) ? $report['count_out_calls'] : 0;
                    $tableBranches['sum']['count_lost_calls'] += !is_null($report['count_lost_calls']) ? $report['count_lost_calls'] : 0;
                    $tableBranches['sum']['count_culations'] += !is_null($report['count_culations']) ? $report['count_culations'] : 0;
                    $tableBranches['sum']['common_culations'] += !is_null($report['common_culations']) ? $report['common_culations'] : 0;
                    $tableBranches['sum']['direct_sample'] += !is_null($report['direct_sample']) ? $report['direct_sample'] : 0;
                    $tableBranches['sum']['count_framework_culations'] += !is_null($report['count_framework_culations']) ? $report['count_framework_culations'] : 0;
                    $tableBranches['sum']['count_bills'] += !is_null($report['count_bills']) ? $report['count_bills'] : 0;
                    $tableBranches['sum']['count_framework_bills'] += !is_null($report['count_framework_bills']) ? $report['count_framework_bills'] : 0;
                    $tableBranches['sum']['common_sum_bills'] += !is_null($report['common_sum_bills']) ? $report['common_sum_bills'] : 0;
                    $tableBranches['sum']['count_payments'] += !is_null($report['count_payments']) ? $report['count_payments'] : 0;
                    $tableBranches['sum']['count_framework_payments'] += !is_null($report['count_framework_payments']) ? $report['count_framework_payments'] : 0;
                    $tableBranches['sum']['common_sum_payments'] += !is_null($report['common_sum_payments']) ? $report['common_sum_payments'] : 0;
                }
                if ($key == $report['branch_id']) {
                    $tableBranches[$key]['sum']['count_clients'] += !is_null($report['count_clients']) ? $report['count_clients'] : 0;
                    $tableBranches[$key]['sum']['leedInWork'] += !is_null($report['count_done_leeds']) ? $report['count_done_leeds'] : 0;

                    $tableBranches[$key]['sum']['count_in_calls'] += !is_null($report['count_in_calls']) ? $report['count_in_calls'] : 0;
                    $tableBranches[$key]['sum']['count_out_calls'] += !is_null($report['count_out_calls']) ? $report['count_out_calls'] : 0;
                    $tableBranches[$key]['sum']['count_lost_calls'] += !is_null($report['count_lost_calls']) ? $report['count_lost_calls'] : 0;
                    $tableBranches[$key]['sum']['count_culations'] += !is_null($report['count_culations']) ? $report['count_culations'] : 0;
                    $tableBranches[$key]['sum']['common_culations'] += !is_null($report['common_culations']) ? $report['common_culations'] : 0;
                    $tableBranches[$key]['sum']['direct_sample'] += !is_null($report['direct_sample']) ? $report['direct_sample'] : 0;
                    $tableBranches[$key]['sum']['count_framework_culations'] += !is_null($report['count_framework_culations']) ? $report['count_framework_culations'] : 0;
                    $tableBranches[$key]['sum']['count_bills'] += !is_null($report['count_bills']) ? $report['count_bills'] : 0;
                    $tableBranches[$key]['sum']['count_framework_bills'] += !is_null($report['count_framework_bills']) ? $report['count_framework_bills'] : 0;
                    $tableBranches[$key]['sum']['common_sum_bills'] += !is_null($report['common_sum_bills']) ? $report['common_sum_bills'] : 0;
                    $tableBranches[$key]['sum']['count_payments'] += !is_null($report['count_payments']) ? $report['count_payments'] : 0;
                    $tableBranches[$key]['sum']['count_framework_payments'] += !is_null($report['count_framework_payments']) ? $report['count_framework_payments'] : 0;
                    $tableBranches[$key]['sum']['common_sum_payments'] += !is_null($report['common_sum_payments']) ? $report['common_sum_payments'] : 0;

                    foreach ($branch as $k => $val) {
                        if ($k == $report['date']) {
                            $tableBranches[$key][$k]['count_clients'] += !is_null($report['count_clients']) ? $report['count_clients'] : 0;
                            $tableBranches[$key][$k]['leedInWork'] += !is_null($report['count_done_leeds']) ? $report['count_done_leeds'] : 0;

                            $tableBranches[$key][$k]['count_in_calls'] += !is_null($report['count_in_calls']) ? $report['count_in_calls'] : 0;
                            $tableBranches[$key][$k]['count_out_calls'] += !is_null($report['count_out_calls']) ? $report['count_out_calls'] : 0;
                            $tableBranches[$key][$k]['count_lost_calls'] += !is_null($report['count_lost_calls']) ? $report['count_lost_calls'] : 0;
                            $tableBranches[$key][$k]['count_culations'] += !is_null($report['count_culations']) ? $report['count_culations'] : 0;
                            $tableBranches[$key][$k]['common_culations'] += !is_null($report['common_culations']) ? $report['common_culations'] : 0;
                            $tableBranches[$key][$k]['direct_sample'] += !is_null($report['direct_sample']) ? $report['direct_sample'] : 0;
                            $tableBranches[$key][$k]['count_framework_culations'] += !is_null($report['count_framework_culations']) ? $report['count_framework_culations'] : 0;
                            $tableBranches[$key][$k]['count_bills'] += !is_null($report['count_bills']) ? $report['count_bills'] : 0;
                            $tableBranches[$key][$k]['count_framework_bills'] += !is_null($report['count_framework_bills']) ? $report['count_framework_bills'] : 0;
                            $tableBranches[$key][$k]['common_sum_bills'] += !is_null($report['common_sum_bills']) ? $report['common_sum_bills'] : 0;
                            $tableBranches[$key][$k]['count_payments'] += !is_null($report['count_payments']) ? $report['count_payments'] : 0;
                            $tableBranches[$key][$k]['count_framework_payments'] += !is_null($report['count_framework_payments']) ? $report['count_framework_payments'] : 0;
                            $tableBranches[$key][$k]['common_sum_payments'] += !is_null($report['common_sum_payments']) ? $report['common_sum_payments'] : 0;
                        }
                    }
                }
            }
        }

        // leedAll - всего лидов;  leedInWork-лиды взятые в работу
        $tableBranches['sum']['leedAll'] = count($newLeeds);
        foreach ($tableBranches as $key => $branch) {
            if ($key != 'sum') {
                foreach ($branch as $k => $v) {
                    if ($k == 'sum') {
                        $tableBranches[$key][$k]['leedAll'] = isset($leeds[$key]['sum']) ? count($leeds[$key]['sum']) : 0;
                    } else {
                        $tableBranches[$key][$k]['leedAll'] = isset($leeds[$key][$k]) ? count($leeds[$key][$k]) : 0;
                    }
                }
            }
        }

        return ['branches' => $branches, 'tableDays' => $tableDays, 'tableBranches' => $tableBranches];
    }

    public static function getUserReportGroup($request)
    {
        if (empty($request)) {
            $date = ['from' => Carbon::make(Carbon::now()->format('Y-m') . '-01')->format('Y-m-d'),
                'to' => Carbon::now()->format('Y-m-d')];
        } else {
            $date = ['from' => Carbon::make($request->dateFrom)->format('Y-m-d'), 'to' => Carbon::make($request->dateTo)->format('Y-m-d')];
        }

        $children = SelectRole::selectRole(Auth::user());

        return UserBranches::query()
            ->select('user_branches.id', 'user_branches.region_id', 'user_branches.group_id', 'user_branches.name',
                'reports.frameworks', 'reports.frameworks_sum',
                'reports.count_out_calls', 'reports.count_in_calls', 'reports.count_lost_calls',
                'reports.count_clients', 'reports.count_culations', 'reports.common_culations', 'reports.direct_sample', 'reports.count_framework_culations',
                'reports.count_bills', 'reports.count_framework_bills', 'reports.common_sum_bills',
                'reports.count_payments', 'reports.count_framework_payments', 'reports.common_sum_payments')
            ->leftJoin(DB::raw("(SELECT *
           FROM (SELECT id as bid, user_branches.name, result_plans.frameworks, result_plans.frameworks_sum
                 FROM user_branches
                        LEFT JOIN (SELECT plans.branch_id       AS plan_brach,
                                          SUM(plans.frameworks) AS frameworks,
                                          SUM(plans.sum)        AS frameworks_sum
                                   FROM (SELECT monthly_plans.branch_id,
                                                monthly_plans.frameworks,
                                                monthly_plans.sum,
                                                CONCAT(monthly_plans.year, '-', monthly_plans.month) AS _date
                                         FROM monthly_plans) AS plans
                                   WHERE plans._date BETWEEN '" . Carbon::make($date['from'])->format('Y-m') . "' and '" . Carbon::make($date['to'])->format('Y-m') . "'
                                   GROUP BY plan_brach) AS result_plans
                                  on user_branches.id = result_plans.plan_brach) AS result_reports
                  LEFT JOIN (SELECT daily_reports.branch_id,
                                    sum(daily_reports.count_out_calls)           AS count_out_calls,
                                    sum(daily_reports.count_in_calls)            AS count_in_calls,
                                    sum(daily_reports.count_lost_calls)          AS count_lost_calls,
                                    sum(daily_reports.count_clients)             AS count_clients,
                                    sum(daily_reports.count_culations)           AS count_culations,
                                    sum(daily_reports.common_culations)          AS common_culations,
                                    sum(daily_reports.direct_sample)             AS direct_sample,
                                    sum(daily_reports.count_framework_culations) AS count_framework_culations,
                                    sum(daily_reports.count_bills)               AS count_bills,
                                    sum(daily_reports.count_framework_bills)     AS count_framework_bills,
                                    sum(daily_reports.common_sum_bills)          AS common_sum_bills,
                                    sum(daily_reports.count_payments)            AS count_payments,
                                    sum(daily_reports.count_framework_payments)  AS count_framework_payments,
                                    sum(daily_reports.common_sum_payments)       AS common_sum_payments
                             FROM daily_reports
                             WHERE date BETWEEN '" . $date['from'] . "' AND '" . $date['to'] . "'
                             GROUP BY branch_id) AS result_plans
                            ON result_reports.bid = result_plans.branch_id) as reports"), 'user_branches.id', '=', 'reports.bid')
            ->where(function ($q) use ($children) {
                foreach ($children->getUserSalon() as $value) {
                    $q->orWhere('id', $value->id);
                }
            })
            ->where(function ($q) use ($request) {
                if($request->salon_id){
                    foreach ($request->salon_id as $salon){
                        $q->orWhere('id', $salon);
                    }
                }elseif ($request->regionManager_id){
                    foreach (UserRm::getSalonsRegionManagers($request) as $salon){
                        $q->orWhere('id', $salon->id);
                    }
                }
                elseif ($request->group_id && $request->group_id != 3) {
                    $q->orWhere('group_id', $request->group_id);
                }
            });

        /*
        select  user_branches.id, user_branches.region_id, user_branches.group_id, user_branches.name,
        reports.frameworks, reports.frameworks_sum,
        reports.count_out_calls, reports.count_in_calls, reports.count_lost_calls,
        reports.count_clients,  reports.count_culations, reports.direct_sample, reports.count_framework_culations,
        reports.count_bills, reports.count_framework_bills, reports.common_sum_bills,
        reports.count_payments, reports.count_framework_payments, reports.common_sum_payments
from  user_branches
left join (SELECT *
           FROM (SELECT id as bid, user_branches.name, result_plans.frameworks, result_plans.frameworks_sum
                 FROM user_branches
                        LEFT JOIN (SELECT plans.branch_id       AS plan_brach,
                                          SUM(plans.frameworks) AS frameworks,
                                          SUM(plans.sum)        AS frameworks_sum
                                   FROM (SELECT monthly_plans.branch_id,
                                                monthly_plans.frameworks,
                                                monthly_plans.sum,
                                                CONCAT(monthly_plans.year, '-', monthly_plans.month) AS _date
                                         FROM monthly_plans) AS plans
                                   WHERE plans._date BETWEEN '2019-12' and '2019-12'
                                   GROUP BY plan_brach) AS result_plans
                                  on user_branches.id = result_plans.plan_brach) AS result_reports
                  LEFT JOIN (SELECT daily_reports.branch_id,
                                    sum(daily_reports.count_out_calls)           AS count_out_calls,
                                    sum(daily_reports.count_in_calls)            AS count_in_calls,
                                    sum(daily_reports.count_lost_calls)          AS count_lost_calls,
                                    sum(daily_reports.count_clients)             AS count_clients,
                                    sum(daily_reports.count_culations)           AS count_culations,
                                    sum(daily_reports.common_culations)          AS common_culations,
                                    sum(daily_reports.direct_sample)             AS direct_sample,
                                    sum(daily_reports.count_framework_culations) AS count_framework_culations,
                                    sum(daily_reports.count_bills)               AS count_bills,
                                    sum(daily_reports.count_framework_bills)     AS count_framework_bills,
                                    sum(daily_reports.common_sum_bills)          AS common_sum_bills,
                                    sum(daily_reports.count_payments)            AS count_payments,
                                    sum(daily_reports.count_framework_payments)  AS count_framework_payments,
                                    sum(daily_reports.common_sum_payments)       AS common_sum_payments
                             FROM daily_reports
                             WHERE date BETWEEN '2019-12-01' AND '2019-12-31'
                             GROUP BY branch_id) AS result_plans
                            ON result_reports.bid = result_plans.branch_id) as reports on user_branches.id = reports.bid
         */

    }

    public static function statisticsSumDate(Request $request)
    {
        $report = self::getUserReportGroup($request)
            ->select(DB::raw('
                                    sum(frameworks)                 AS frameworks, 
                                    sum(frameworks_sum)             AS frameworks_sum, 
                                    sum(count_out_calls)            AS count_out_calls,
                                    sum(count_in_calls)             AS count_in_calls,
                                    sum(count_lost_calls)           AS count_lost_calls,
                                    sum(count_clients)              AS count_clients,
                                    sum(count_culations)            AS count_culations,
                                    sum(common_culations)           AS common_culations,
                                    sum(direct_sample)              AS direct_sample,
                                    sum(count_framework_culations)  AS count_framework_culations,
                                    sum(count_bills)                AS count_bills,
                                    sum(count_framework_bills)      AS count_framework_bills,
                                    sum(common_sum_bills)           AS common_sum_bills,
                                    sum(count_payments)             AS count_payments,
                                    sum(count_framework_payments)   AS count_framework_payments,
                                    sum(common_sum_payments)        AS common_sum_payments'))
            ->first();

        return [
            $report->frameworks ?? 0,
            $report->frameworks_sum ?? 0,
            $report->count_clients ?? 0,
            $report->count_out_calls ?? 0,
            $report->count_in_calls ?? 0,
//            $report->count_lost_calls ?? 0,
//            $report->count_culations ?? 0,
//            $report->common_culations ?? 0,
//            $report->direct_sample ?? 0,
//            $report->count_framework_culations ?? 0,
//            $report->count_bills ?? 0,
//            $report->count_framework_bills ?? 0,
//            $report->common_sum_bills ?? 0,
            $report->count_payments ?? 0,
            $report->count_framework_payments ?? 0,
            $report->common_sum_payments ?? 0,
            ($report->frameworks ? round(($report->count_framework_payments / $report->frameworks) * 100, 2) . ' %' : '0 %'),
            ($report->frameworks_sum ? round(($report->common_sum_payments / $report->frameworks_sum) * 100, 2) . ' %' : '0 %')

        ];

    }
}