<?php
/**
 * Created by PhpStorm.
 * User: pomazan_rn
 * Date: 30.01.2019
 * Time: 13:23
 */

namespace App\Support;


use App\DailyReport;
use App\MonthlyPlan;
use App\Regions;
use App\UserBranches;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Date\Date;

class Support
{

    static public function contenHeaderManager()
    {
        $date = Carbon::now();
        $dayInMoth = $date->format('t');
        $user = Auth::user();
        $branch = UserBranches::find($user->branch_id) ? UserBranches::find($user->branch_id) : 0;


        $qeuryPlan = MonthlyPlan::where('branch_id', $branch->id)
            ->where('month', (int)$date->format('n'))
            ->where('year', (int)$date->format('Y'))->first();
        $monthPlan = !is_null($qeuryPlan) ? $qeuryPlan : 0;


        $dailyAllReports = DailyReport::where('branch_id', $user->branch_id)->get();

        $dailyReports = [];
        foreach ($dailyAllReports as $report) {
            if ($report->created_at >= (Carbon::make('01-' . $date->format('m-Y') . '00:00:00')->format('Y-m-d H:i:s')) && $report->created_at <= Carbon::make($dayInMoth . '-' . $date->format('m-Y') . '00:00:00')->format('Y-m-d H:i:s')) {
                $dailyReports[] = $report;
            }
        }

        $regions = Regions::where('id',$branch->region_id)->first();

        $sum_payments = 0;
        $framework_payments = 0;

        if (!empty($dailyReports)) {
            foreach ($dailyReports as $val) {
            $framework_payments += $val->count_framework_payments;
            $sum_payments += $val->common_sum_payments;
            }
        }

        $frameworkPercent = (empty($monthPlan) ? 0 : round(($framework_payments / $monthPlan->frameworks * 100), 2));
        $sumPercent = (empty($monthPlan) ? 0 : round(($sum_payments / $monthPlan->sum * 100), 2));

        return [
            'regions' => $regions,
            'date' => $date,
            'user' => $user,
            'branch' => $branch,
            'monthPlan' => $monthPlan,
            'dailyReport' => $dailyReports,
            'sum_payments' => $sum_payments,
            'framework_payments' => $framework_payments,
            'frameworkPercent' => $frameworkPercent,
            'sumPercent' => $sumPercent
        ];
    }
}