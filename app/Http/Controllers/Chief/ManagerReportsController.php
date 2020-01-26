<?php

namespace App\Http\Controllers\Chief;

use App\DailyReport;
use App\Support\ManagerReport\ManagerReports;
use App\User;
use App\UserBranches;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManagerReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role_id > 2){
            return redirect('/');
        }

        return view('managerReports/managerReports');
    }

    public function getReports(Request $request)
    {
        return ManagerReports::getManagerReport($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->group_id == 3){
            $branchAll = UserBranches::select('id', 'name')->get()->toArray();
            $userAll = User::select('id', 'name', 'role_id')->where('role_id', 3)->get()->toArray();

        }else{
            $userAll = User::select('id', 'name', 'role_id','branch_id')
                ->where('group_id', $user->group_id)
                ->where('role_id', 3)
                ->get()->toArray();

            $branchAll = UserBranches::select('id', 'name')
                ->where(function ($q) use ($userAll){
                foreach ($userAll as $value){
                    $q->orWhere('id', $value['branch_id']);
                }
            })->get()->toArray();
        }


        return view('managerReports/managerReportsCreate', [
            'users' => $userAll,
            'branches' =>$branchAll
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|integer|min:0',
            'user_id' => 'required|integer|min:0',
            'count_clients' => 'required|integer|min:0',
            'count_done_leeds' => 'required|integer|min:0',
            'count_in_calls' => 'required|integer|min:0',
            'count_out_calls' => 'required|integer|min:0',
            'count_culations' => 'required|integer|min:0',
            'common_culations' => 'required|integer|min:0',
            'direct_sample' => 'required|integer|min:0',
            'count_framework_culations' => 'required|integer|min:0',
            'count_bills' => 'required|integer|min:0',
            'count_framework_bills' => 'required|integer|min:0',
            'common_sum_bills' => 'required|integer|min:0',
            'count_payments' => 'required|integer|min:0',
            'count_framework_payments' => 'required|integer|min:0',
            'common_sum_payments' => 'required|integer|min:0',
            'date' => 'required|date|date_format:Y-m-d',
        ], [
            'branch_id.required' => 'Выберите точку продаж',
            'user_id.required' => 'Выберите менеджера',
            'count_clients.required' => 'Заполните ячейку "Посетители"',
            'count_done_leeds.required' => 'Заполните ячейку "Количество обработанных лидов"',
            'count_in_calls.required' => 'Заполните ячейку "Количество входящих звонков(уникальных клиентов)"',
            'count_out_calls.required' => 'Заполните ячейку "Количество исходящие звонки(уникальных клиентов)"',
            'count_culations.required' => 'Заполните ячейку "Количество просчитанных конструкций в шт."',
            'common_culations.required' => 'Заполните ячейку "Общая сумма просчетов"',
            'direct_sample.required' => 'Заполните ячейку "Направленно на замер"',
            'count_framework_culations.required' => 'Заполните ячейку "Количество конструкций в замерах"',
            'count_bills.required' => 'Заполните ячейку "Количество выставленных счетов"',
            'count_framework_bills.required' => 'Заполните ячейку "Количество конструкций в счетах"',
            'common_sum_bills.required' => 'Заполните ячейку "Общая сумма в счетах"',
            'count_payments.required' => 'Заполните ячейку "Количество оплат"',
            'count_framework_payments.required' => 'Заполните ячейку "Количество конструкций в оплатах"',
            'common_sum_payments.required' => 'Заполните ячейку "Общая сумма в оплатах"',
            'date.required' => 'Установите дату',
        ]);

        if ($validator->fails()){

            return response($validator->errors()->all());
        }


        $branch = DailyReport::where('branch_id', $request->branch_id)
            ->where('user_id', $request->user_id)
            ->where('date', $request->date)
            ->get()->toArray();

        if(!$branch){
            $report = new DailyReport();
            $report->branch_id = $request->branch_id;
            $report->user_id = $request->user_id;
            $report->count_clients = $request->count_clients;
            $report->count_done_leeds = $request->count_done_leeds;
            $report->count_in_calls = $request->count_in_calls;
            $report->count_out_calls = $request->count_out_calls;
            $report->count_culations = $request->count_culations;
            $report->common_culations = $request->common_culations;
            $report->direct_sample = $request->direct_sample;
            $report->count_framework_culations = $request->count_framework_culations;
            $report->count_bills = $request->count_bills;
            $report->count_framework_bills = $request->count_framework_bills;
            $report->common_sum_bills = $request->common_sum_bills;
            $report->count_payments = $request->count_payments;
            $report->count_framework_payments = $request->count_framework_payments;
            $report->common_sum_payments = $request->common_sum_payments;
            $report->date = $request->date;
            $report->save();
        }else{
            return response(['Отчет данной точки продаж уже существует за текущею дату ']);
        }

        return response()->make('Отчет добавлен!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        if ($user->role_id > 2){
            return redirect('/');
        }

        if ($user->group_id == 3){
            $branchAll = UserBranches::select('id', 'name')->get()->toArray();
            $userAll = User::select('id', 'name', 'role_id')->where('role_id', 3)->get()->toArray();

        }else{
            $userAll = User::select('id', 'name', 'role_id','branch_id')
                ->where('group_id', $user->group_id)
                ->where('role_id', 3)
                ->get()->toArray();

            $branchAll = UserBranches::select('id', 'name')
                ->where(function ($q) use ($userAll){
                    foreach ($userAll as $value){
                        $q->orWhere('id', $value['branch_id']);
                    }
                })->get()->toArray();
        }
        $report = DailyReport::find($id)->toArray();
        $branches = [];
        foreach ($branchAll as $value){
            $branches[$value['id']] = $value;
        }
        $users = [];
        foreach ($userAll as $value){
            $users[$value['id']] = $value;
        }

        return view('managerReports/managerReportsEdit', [
            'users' => $users,
            'branches' =>$branches,
            'report' => $report
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|integer|min:0',
            'user_id' => 'required|integer|min:0',
            'count_clients' => 'required|integer|min:0',
            'count_done_leeds' => 'required|integer|min:0',
            'count_in_calls' => 'required|integer|min:0',
            'count_out_calls' => 'required|integer|min:0',
            'count_culations' => 'required|integer|min:0',
            'common_culations' => 'required|integer|min:0',
            'direct_sample' => 'required|integer|min:0',
            'count_framework_culations' => 'required|integer|min:0',
            'count_bills' => 'required|integer|min:0',
            'count_framework_bills' => 'required|integer|min:0',
            'common_sum_bills' => 'required|integer|min:0',
            'count_payments' => 'required|integer|min:0',
            'count_framework_payments' => 'required|integer|min:0',
            'common_sum_payments' => 'required|integer|min:0',
            'date' => 'required|date|date_format:Y-m-d',
        ], [
            'branch_id.required' => 'Выберите точку продаж',
            'user_id.required' => 'Выберите менеджера',
            'count_clients.required' => 'Заполните ячейку "Посетители"',
            'count_done_leeds.required' => 'Заполните ячейку "Количество обработанных лидов"',
            'count_in_calls.required' => 'Заполните ячейку "Количество входящих звонков(уникальных клиентов)"',
            'count_out_calls.required' => 'Заполните ячейку "Количество исходящие звонки(уникальных клиентов)"',
            'count_culations.required' => 'Заполните ячейку "Количество просчитанных конструкций в шт."',
            'common_culations.required' => 'Заполните ячейку "Общая сумма просчетов"',
            'direct_sample.required' => 'Заполните ячейку "Направленно на замер"',
            'count_framework_culations.required' => 'Заполните ячейку "Количество конструкций в замерах"',
            'count_bills.required' => 'Заполните ячейку "Количество выставленных счетов"',
            'count_framework_bills.required' => 'Заполните ячейку "Количество конструкций в счетах"',
            'common_sum_bills.required' => 'Заполните ячейку "Общая сумма в счетах"',
            'count_payments.required' => 'Заполните ячейку "Количество оплат"',
            'count_framework_payments.required' => 'Заполните ячейку "Количество конструкций в оплатах"',
            'common_sum_payments.required' => 'Заполните ячейку "Общая сумма в оплатах"',
            'date.required' => 'Установите дату',
        ]);

        if ($validator->fails()){

            return response($validator->errors()->all());

        }else{
            $report = DailyReport::where('id', $id)->first();
            $report->branch_id = $request->branch_id;
            $report->user_id = $request->user_id;
            $report->count_clients = $request->count_clients;
            $report->count_done_leeds = $request->count_done_leeds;
            $report->count_in_calls = $request->count_in_calls;
            $report->count_out_calls = $request->count_out_calls;
            $report->common_culations = $request->common_culations;
            $report->count_culations = $request->count_culations;
            $report->direct_sample = $request->direct_sample;
            $report->count_framework_culations = $request->count_framework_culations;
            $report->count_bills = $request->count_bills;
            $report->count_framework_bills = $request->count_framework_bills;
            $report->common_sum_bills = $request->common_sum_bills;
            $report->count_payments = $request->count_payments;
            $report->count_framework_payments = $request->count_framework_payments;
            $report->common_sum_payments = $request->common_sum_payments;
            $report->date = $request->date;
            $report->update();

            return response()->make('Отчет добавлен!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        return redirect('/');
    }
}
