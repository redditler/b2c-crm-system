<?php

namespace App\Http\Controllers;

use App\DailyReport;
use App\LeedIps;
use App\MonthlyPlan;
use App\User;
use App\UserBranches;
use App\Regions;
use App\UserGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Leed;
use App\Contact;
use App\ContactPhones;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;
use DB;

class MonthlyPlanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     *
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

        return view('reports.monthly-plan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->role_id > 2) {
            return redirect('monthly-plan')->with('error', 'Нет доступа!');
        }

        $years = $this->getYears();
        $months = self::getMonths();

        $branches = UserBranches::query()
            ->where(function ($q){
                foreach (UserGroups::getUserGroup() as $group){
                    $q->orWhere('group_id', $group->id);
                }
            })
            ->get()->pluck('name', 'id');

        return view('reports.create-monthly-plan', compact('years', 'branches', 'months'));
    }

    public function edit($id)
    {
        if (Auth::user()->role_id > 2) {
            return redirect('monthly-plan')->with('error', 'Нет доступа!');
        }

        $monthly_plan = MonthlyPlan::findOrFail($id);
        $years = $this->getYears();
        $months = self::getMonths();
        $branches = UserBranches::all()->pluck('name', 'id');

        return view('reports.edit-monthly-plan', compact('years', 'branches', 'months', 'monthly_plan'));
    }

    public function getYears()
    {
        $years = [
            (int)date('Y') => (int)date('Y'),
            (int)date('Y') + 1 => (int)date('Y') + 1,
            (int)date('Y') + 2 => (int)date('Y') + 2
        ];
        return $years;
    }

    public static function getMonths()
    {
        Date::setLocale('ru');
        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            $date = new Date(date('Y') . '-' . $i . '-' . date('d'));
            $months[$i] = $date->format('F');
        }
        return $months;
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
        $year = $request->year;
        $month = $request->month;
        $branch_id = $request->branch_id;
        $validator = Validator::make($request->all(), [
            'year' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 2),
            'month' => [
                'required',
                'numeric',
                'min:1',
                'max:12',
                Rule::unique('monthly_plans')->where(function ($query) use ($year, $month, $branch_id) {
                    $query->where('year', '=', $year)->where('month', '=', $month)->where('branch_id', '=', $branch_id);
                })
            ],
            'branch_id' => 'required|numeric',
            'frameworks' => 'required|numeric',
            'sum' => 'required|numeric',
            'active' => 'nullable|sometimes'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {
            $monthly_plans = new MonthlyPlan;
            $monthly_plans->fill($request->all());
            $monthly_plans->save();
            return redirect('monthly-plan')->with('success', 'План добавлен!');
        }
    }

    public function getMonthlyPlans(Request $request)
    {
        if (Auth::user()->role_id <= 2) {
            $monthly_plans = MonthlyPlan::query()
            ->where(function ($q){
                foreach (UserBranches::getMonthlyPlanBranches() as $branch){
                    $q->orWhere('branch_id', $branch->id);
                }
            });
        }

        return Datatables::of($monthly_plans)
            ->editColumn('month', function ($monthly_plan) {
                Date::setLocale('ru');
                $date = new Date(date('Y') . '-' . $monthly_plan->month . '-' . date('d'));
                $months = $date->format('F');
                return $months;
            })
            ->editColumn('branch_id', function ($monthly_plan) {
                return $monthly_plan->branch->name;
            })
            ->addColumn('btn', function ($monthly_plan) {
                return '<a href="/monthly-plan/' . $monthly_plan->id . '/edit" class="btn btn-block btn-primary btn-sm"> Изменить
                план</a>';
            })
            ->filterColumn('branch_id', function ($query, $keyword) {
                $query->whereHas('branch', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->escapeColumns([])
            ->make();
    }

    public function update(Request $request, $id)
    {
        $year = $request->year;
        $month = $request->month;
        $branch_id = $request->branch_id;

        $validator = Validator::make($request->all(), [
            'year' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 2),
            'month' => [
                'required',
                'numeric',
                'min:1',
                'max:12',
                Rule::unique('monthly_plans')->where(function ($query) use ($year, $month, $branch_id, $id) {
                    $query->where('year', '=', $year)->where('month', '=', $month)->where('branch_id', '=', $branch_id)->where('id', '<>', $id);
                })
            ],
            'branch_id' => 'required|numeric',
            'frameworks' => 'required|numeric',
            'sum' => 'required|numeric',
            'active' => 'nullable|sometimes'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {
            if (!$request->has('active')) {
                $request->merge(['active' => 0]);
            }
            $monthly_plans = MonthlyPlan::findOrFail($id);
            $monthly_plans->fill($request->all());
            $monthly_plans->save();
            return redirect('monthly-plan')->with('success', 'План изменен!');
        }
    }

}
