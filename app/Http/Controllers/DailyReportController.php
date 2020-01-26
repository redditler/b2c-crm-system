<?php

namespace App\Http\Controllers;

use App\DailyReport;
use App\LeedIps;
use App\MonthlyPlan;
use App\Regions;
use App\MonthlyPlans;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Leed;
use App\Contact;
use App\ContactPhones;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use DB;
use Auth;
use Illuminate\Validation\Rule;

class DailyReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $date = Carbon::now()->format('Y-m-d');

        if ($user->role_id != 3) {
            return redirect('/');
        }

        $report = DailyReport::where('user_id', $user->id)
            ->where('date', $date)
            ->first();


        if (!empty($report)) {
            return redirect()->route('daily-reports.edit', ['daily_report' => $report['id']]);
        }


        return view('reports.daily-report');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('/');
    }

    public function show()
    {
        return redirect('/');
    }


    public function edit($id)
    {
        $user = Auth::user();
        if ($user->role_id != 3) {
            return redirect('/');
        }

        $report = DailyReport::where ('id',$id)
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->where('user_id', $user->id)
            ->first();
        if (!isset($report)){
            return redirect('/');
        }
        return view('reports.edit-daily-report', [
            'id' => $id,
            'report' => $report->toArray()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $date = Carbon::now()->format('Y-m-d');

        $checkReport = DailyReport::query()
            ->where('date', $date)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($checkReport)){
            return response()->make('Отчет уже добавлен!');
        }

        $validator = Validator::make($request->all(), [

            'count_clients' => 'required|integer|min:0',
            'count_done_leeds' => 'required|integer|min:0',
            'count_in_calls' => 'required|integer|min:0',
            'count_out_calls' => 'required|integer|min:0',
//            'count_culations' => 'required|integer|min:0',
//            'common_culations' => 'required|integer|min:0',
//            'direct_sample' => 'required|integer|min:0',
//            'count_framework_culations' => 'required|integer|min:0',
//            'count_bills' => 'required|integer|min:0',
//            'count_framework_bills' => 'required|integer|min:0',
//            'common_sum_bills' => 'required|integer|min:0',
            'count_payments' => 'required|integer|min:0',
            'count_framework_payments' => 'required|integer|min:0',
            'common_sum_payments' => 'required|integer|min:0',
        ], [
            'count_clients.required' => 'Заполните ячейку "Посетители"',
            'count_done_leeds.required' => 'Заполните ячейку "Количество обработанных лидов"',
            'count_in_calls.required' => 'Заполните ячейку "Количество входящих звонков(уникальных клиентов)"',
            'count_out_calls.required' => 'Заполните ячейку "Количество исходящие звонки(уникальных клиентов)"',
//            'count_culations.required' => 'Заполните ячейку "Количество просчитанных конструкций в шт."',
//            'common_culations.required' => 'Заполните ячейку "Общая сумма просчетов"',
//            'direct_sample.required' => 'Заполните ячейку "Направленно на замер"',
//            'count_framework_culations.required' => 'Заполните ячейку "Количество конструкций в замерах"',
//            'count_bills.required' => 'Заполните ячейку "Количество выставленных счетов"',
//            'count_framework_bills.required' => 'Заполните ячейку "Количество конструкций в счетах"',
//            'common_sum_bills.required' => 'Заполните ячейку "Общая сумма в счетах"',
            'count_payments.required' => 'Заполните ячейку "Количество оплат"',
            'count_framework_payments.required' => 'Заполните ячейку "Количество конструкций в оплатах"',
            'common_sum_payments.required' => 'Заполните ячейку "Общая сумма в оплатах"',
        ]);

        if ($validator->fails()) {

            return response($validator->errors()->all());
        }

        DB::transaction(function () use($user, $request, $date){
            $report = new DailyReport();
            $report->branch_id = $user->branch_id;
            $report->user_id = $user->id;
            $report->count_clients = $request->count_clients;
            $report->count_done_leeds = $request->count_done_leeds;
            $report->count_in_calls = $request->count_in_calls;
            $report->count_out_calls = $request->count_out_calls;
//            $report->common_culations = $request->common_culations;
//            $report->count_culations = $request->count_culations;
//            $report->direct_sample = $request->direct_sample;
//            $report->count_framework_culations = $request->count_framework_culations;
//            $report->count_bills = $request->count_bills;
//            $report->count_framework_bills = $request->count_framework_bills;
//            $report->common_sum_bills = $request->common_sum_bills;
            $report->count_payments = $request->count_payments;
            $report->count_framework_payments = $request->count_framework_payments;
            $report->common_sum_payments = $request->common_sum_payments;
            $report->date = $date;
            $report->save();
        });

        return response()->make('Отчет добавлен!');


    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $date = Carbon::now()->format('Y-m-d');

        $validator = Validator::make($request->all(), [

            'count_clients' => 'required|integer|min:0',
            'count_done_leeds' => 'required|integer|min:0',
            'count_in_calls' => 'required|integer|min:0',
            'count_out_calls' => 'required|integer|min:0',
//            'count_culations' => 'required|integer|min:0',
//            'common_culations' => 'required|integer|min:0',
//            'direct_sample' => 'required|integer|min:0',
//            'count_framework_culations' => 'required|integer|min:0',
//            'count_bills' => 'required|integer|min:0',
//            'count_framework_bills' => 'required|integer|min:0',
//            'common_sum_bills' => 'required|integer|min:0',
            'count_payments' => 'required|integer|min:0',
            'count_framework_payments' => 'required|integer|min:0',
            'common_sum_payments' => 'required|integer|min:0',
        ], [
            'count_clients.required' => 'Заполните ячейку "Посетители"',
            'count_done_leeds.required' => 'Заполните ячейку "Количество обработанных лидов"',
            'count_in_calls.required' => 'Заполните ячейку "Количество входящих звонков(уникальных клиентов)"',
            'count_out_calls.required' => 'Заполните ячейку "Количество исходящие звонки(уникальных клиентов)"',
//            'count_culations.required' => 'Заполните ячейку "Количество просчитанных конструкций в шт."',
//            'common_culations.required' => 'Заполните ячейку "Общая сумма просчетов"',
//            'direct_sample.required' => 'Заполните ячейку "Направленно на замер"',
//            'count_framework_culations.required' => 'Заполните ячейку "Количество конструкций в замерах"',
//            'count_bills.required' => 'Заполните ячейку "Количество выставленных счетов"',
//            'count_framework_bills.required' => 'Заполните ячейку "Количество конструкций в счетах"',
//            'common_sum_bills.required' => 'Заполните ячейку "Общая сумма в счетах"',
            'count_payments.required' => 'Заполните ячейку "Количество оплат"',
            'count_framework_payments.required' => 'Заполните ячейку "Количество конструкций в оплатах"',
            'common_sum_payments.required' => 'Заполните ячейку "Общая сумма в оплатах"',
        ]);

        if ($validator->fails()) {

            return response($validator->errors()->all());
        }

        try {
            DailyReport::where('id',$id)->update([
                'branch_id' => $user->branch_id,
                'user_id' => $user->id,
                'count_clients' => $request->count_clients,
                'count_done_leeds' => $request->count_done_leeds,
                'count_in_calls' => $request->count_in_calls,
                'count_out_calls' => $request->count_out_calls,
//                'common_culations' => $request->common_culations,
//                'count_culations' => $request->count_culations,
//                'direct_sample' => $request->direct_sample,
//                'count_framework_culations' => $request->count_framework_culations,
//                'count_bills' => $request->count_bills,
//                'count_framework_bills' => $request->count_framework_bills,
//                'common_sum_bills' => $request->common_sum_bills,
                'count_payments' => $request->count_payments,
                'count_framework_payments' => $request->count_framework_payments,
                'common_sum_payments' => $request->common_sum_payments,
                'date' => $date,
                'updated_at' => Carbon::now()
            ]);
        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        }



        return response()->make('Отчет добавлен!');
    }

}
