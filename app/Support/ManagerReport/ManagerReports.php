<?php
/**
 * Created by PhpStorm.
 * User: pomazan_rn
 * Date: 19.11.2019
 * Time: 11:38
 */

namespace App\Support\ManagerReport;


use App\DailyReport;
use App\Http\Controllers\Chief\ManagerReportsController;
use App\Support\LeadFilter\LeadFilterRender;
use App\Support\UserRole\SelectRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ManagerReports
{
    public static function getManagerReport($request)
    {
        return self::getBuildTable($request);
    }

    private static function getReports($request)
    {
        $dateFrom = !empty($request->dateFrom) ? $request->dateFrom : Carbon::make(Carbon::now()->format('Y-m') . '-01')->format('Y-m-d');
        $dateTo = !empty($request->dateTo) ? $request->dateTo : Carbon::now()->format('Y-m-d');
        $filter = LeadFilterRender::chooseFilterMethod($request) ?? false;

        return DailyReport::query()
            ->whereBetween('date',[$dateFrom, $dateTo])
            ->orderBy('date', 'desc')
            ->where(function ($q){
                foreach (SelectRole::selectRole(Auth::user())->getUserSalon() as $item){
                    $q->orWhere('branch_id', $item->id);
                }
            })
            ->where(function ($q) use ($filter){
                if ($filter) {
                    foreach ($filter as $value) {
                        $q->orWhere('user_id', $value->id);//@TODO
                    }
                }
            })
            ->with('manager')
            ->with('branch');
    }

    private static function getBuildTable($request)
    {
        $reports = self::getReports($request);
        return Datatables::of($reports)
            ->addColumn('action', function ($reports) {
                return '<a href="managerReports/'.$reports->id.'/edit" class="btn btn-success ">Изменить</a>';
            })
            ->orderColumn('created_at', 'created_at $1')
            ->addColumn('created_at', function ($reports) {
                return $reports->created_at->format('Y-m-d');
            })
            ->orderColumn('branch_id', 'branch_id $1')
            ->addColumn('branch_id', function ($reports) {
                return $reports->branch->name.'<br/>'.$reports->branch->groups->name;
            })
            ->orderColumn('user_id', 'user_id $1')
            ->addColumn('user_id', function ($reports) {
                return $reports->manager->name;
            })
            ->orderColumn('count_bills', 'count_bills $1')
            ->addColumn('count_bills', function ($reports) {
                return $reports->count_bills;
            })
            ->orderColumn('count_framework_bills', 'count_framework_bills $1')
            ->addColumn('count_framework_bills', function ($reports) {
                return $reports->count_framework_bills;
            })
            ->orderColumn('common_sum_bills', 'common_sum_bills $1')
            ->addColumn('common_sum_bills', function ($reports) {
                return $reports->common_sum_bills;
            })
            ->orderColumn('count_payments', 'count_payments $1')
            ->addColumn('count_payments', function ($reports) {
                return $reports->count_payments;
            })
            ->orderColumn('count_framework_payments', 'count_framework_payments $1')
            ->addColumn('count_framework_payments', function ($reports) {
                return $reports->count_framework_payments;
            })
            ->orderColumn('common_sum_payments', 'common_sum_payments $1')
            ->addColumn('common_sum_payments', function ($reports) {
                return $reports->common_sum_payments;
            })
            ->escapeColumns([])
            ->make(true);
    }

}