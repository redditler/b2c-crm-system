<?php
/**
 * Created by PhpStorm.
 * User: pomazan_rn
 * Date: 29.11.2019
 * Time: 14:31
 */

namespace App\Support\StatisticTable;


use App\DailyReport;
use Yajra\DataTables\Facades\DataTables;

class StatisticTables
{
    static public function statisticChief($request)
    {
        $reports = DailyReport::getUserReportGroup($request);


        return Datatables::of($reports)
            ->orderColumn('branch_id', 'branch_id $1')
            ->addColumn('branch_id', function ($reports) {//Название точки продаж
                return $reports->name;
            })
            ->orderColumn('frameworks', 'frameworks $1')
            ->addColumn('frameworks', function ($reports) {//План по конструкциям
                return $reports->frameworks ?? 0;
            })
            ->orderColumn('frameworks_sum', 'frameworks_sum $1')
            ->addColumn('frameworks_sum', function ($reports) {//План по грн
                return $reports->frameworks_sum ?? 0;
            })
            ->orderColumn('count_clients', 'count_clients $1')//Посетители
            ->addColumn('count_clients', function ($reports) {
                return $reports->count_clients ?? 0;
            })
            ->orderColumn('count_in_calls', 'count_in_calls $1')
            ->addColumn('count_in_calls', function ($reports) {//Входящие звонки
                return $reports->count_in_calls ?? 0;
            })
            ->orderColumn('count_out_calls', 'count_out_calls $1')
            ->addColumn('count_out_calls', function ($reports) {//Исходящие звонки
                return $reports->count_out_calls ?? 0;
            })
//            ->orderColumn('count_lost_calls', 'count_lost_calls $1')
//            ->addColumn('count_lost_calls', function ($reports) {//Пропущенные звонки
//                return $reports->count_lost_calls ?? 0;
//            })
//            ->orderColumn('count_culations', 'count_culations $1')
//            ->addColumn('count_culations', function ($reports) {//Количество просчитанных конструкций
//                return $reports->count_culations ?? 0;
//            })
//            ->orderColumn('common_culations', 'name $1')
//            ->addColumn('common_culations', function ($reports) {//Общая сумма просчетов
//                return $reports->common_culations ?? 0;
//            })
//            ->orderColumn('direct_sample', 'direct_sample $1')
//            ->addColumn('direct_sample', function ($reports) {//Направленно на замер
//                return $reports->direct_sample ?? 0;
//            })
//            ->orderColumn('count_framework_culations', 'count_framework_culations $1')
//            ->addColumn('count_framework_culations', function ($reports) {//Количество конструкций в замерах
//                return $reports->count_framework_culations ?? 0;
//            })
//            ->orderColumn('count_bills', 'count_bills $1')
//            ->addColumn('count_bills', function ($reports) {//Количество выставленных счетов
//                return $reports->count_bills ?? 0;
//            })
//            ->orderColumn('count_framework_bills', 'count_framework_bills $1')
//            ->addColumn('count_framework_bills', function ($reports) {//Количество конструкций в счетах
//                return $reports->count_framework_bills ?? 0;
//            })
//            ->orderColumn('common_sum_bills', 'common_sum_bills $1')
//            ->addColumn('common_sum_bills', function ($reports) {//Общая сумма в счетах
//                return $reports->common_sum_bills ?? 0;
//            })
            ->orderColumn('count_payments', 'count_payments $1')
            ->addColumn('count_payments', function ($reports) {//Количество оплат
                return $reports->count_payments ?? 0;
            })
            ->orderColumn('count_framework_payments', 'count_framework_payments $1')
            ->addColumn('count_framework_payments', function ($reports) {//Количество конструкций в оплатах
                return $reports->count_framework_payments ?? 0;
            })
            ->orderColumn('common_sum_payments', 'common_sum_payments $1')
            ->addColumn('common_sum_payments', function ($reports) {//Общая сумма в оплатах
                return $reports->common_sum_payments ?? 0;
            })
            ->addColumn('frameworks_percent', function ($reports) {//Процент выполнения кончтрукций
                if ($reports->frameworks != 0) {
                    return round(($reports->count_framework_payments / $reports->frameworks)*100, 2) . ' %';
                } else {
                    return '0%';
                }
            })
            ->addColumn('frameworks_sum_percent', function ($reports) {//Процент выполнения грн
                if ($reports->frameworks_sum != 0) {
                    return round(($reports->common_sum_payments / $reports->frameworks_sum)*100, 2) . ' %';
                } else {
                    return '0%';
                }
            })
            ->escapeColumns([])
            ->make(true);


    }
}