<?php

namespace App\Http\Controllers;

use App\FinReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Leed;
use App\Contact;
use App\ContactPhones;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;
use DB;
use Auth;

class FinPlanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reports.fin-reports');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reports.create-fin-report');
    }

    public function edit($id)
    {
        $fin_report = FinReport::findOrFail($id);
        $active = false;
        if (!empty($fin_report->installer) || !empty($fin_report->area) || !empty($fin_report->sum)) {
            $active = true;
        }
        return view('reports.edit-fin-report', compact('fin_report', 'active'));
    }

    public function getFinPlans(Request $request)
    {
        $date = $request->date_stat;
        $month = $request->month_stat;
        $year = $request->year_stat;
        $branch_id = $request->branch_id;

        if (!Auth::user()->manager) {
            $fin_reports = FinReport::select([
                'user_id',
                'branch_id',
                'num_order',
                'sum_order',
                'framework_count',
                'discount',
                'name',
                'phone',
                'email',
                'city',
                'street',
                'house',
                'flat',
                'installer',
                'area',
                'date',
                'sum'
            ]);

            if ($branch_id != 0) {
                $fin_reports = $fin_reports->where('branch_id', '=', $branch_id);
            }
        } else {
            $fin_reports = FinReport::select([
                'id',
                'user_id',
                'branch_id',
                'num_order',
                'sum_order',
                'framework_count',
                'discount',
                'name',
                'phone',
                'email',
                'city',
                'street',
                'house',
                'flat',
                'installer',
                'area',
                'date',
                'sum'
            ])
                ->where('user_id', '=', Auth::user()->id);
        }


        if (!empty($date)) {
            $fin_reports = $fin_reports->where('date', '=', $date);
        } elseif (!empty($month)) {
            $fin_reports = $fin_reports->where(DB::raw('MONTH(date)=' . $month . ' AND YEAR(date) = YEAR(CURDATE())'));
        } elseif (!empty($year)) {
            $fin_reports = $fin_reports->where(DB::raw('YEAR(date) = YEAR(CURDATE())'));
        }

        return Datatables::of($fin_reports)
            ->editColumn('sum_order', function ($fin_report) {
                return $fin_report->sum_order . ' грн';
            })
            ->editColumn('sum', function ($fin_report) {
                return $fin_report->sum . ' грн';
            })
            ->editColumn('area', function ($fin_report) {
                return $fin_report->area . ' м²';
            })
            ->addColumn('btn', function ($fin_report) {
                return '<a href="/fin-reports/' . $fin_report->id . '/edit" class="btn fin-rep-btn"> <span><img src="/img/shape.png" alt=""></span></a>';
            })
            ->addColumn('adres', function ($fin_report) {
                return $fin_report->street . ' ' . $fin_report->house . ' ' . $fin_report->flat;
            })
            ->escapeColumns([])
            ->make();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report.*.date' => 'required|date|date_format:Y-m-d',
            'report.*.user_id' => 'required|numeric',
            'report.*.branch_id' => 'required|numeric',
            'report.*.num_order' => 'required|numeric',
            'report.*.sum_order' => 'required|numeric',
            'report.*.framework_count' => 'required|numeric',
            'report.*.discount' => 'required|numeric',
            'report.*.name' => 'required|max:255',
            'report.*.phone' => 'required|digits:10',
            'report.*.email' => 'required|email|max:255',
            'report.*.city' => 'required|max:255',
            'report.*.street' => 'required|max:255',
            'report.*.house' => 'required|numeric',
            'report.*.flat' => 'required|numeric',
            'report.*.installer' => 'nullable|sometimes|max:255',
            'report.*.area' => 'nullable|sometimes|numeric',
            'report.*.sum' => 'nullable|sometimes|numeric'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {
            foreach ($request->all()['report'] AS $rep) {
                $fin_report = new FinReport;
                $fin_report->fill($rep);
                $fin_report->save();
            }
            return redirect('fin-reports')->with('success', 'Отчет добавлен!');
        }
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|date_format:Y-m-d',
            'user_id' => 'required|numeric',
            'branch_id' => 'required|numeric',
            'num_order' => 'required|numeric',
            'sum_order' => 'required|numeric',
            'framework_count' => 'required|numeric',
            'discount' => 'required|numeric',
            'name' => 'required|max:255',
            'phone' => 'required|digits:10',
            'email' => 'required|email|max:255',
            'city' => 'required|max:255',
            'street' => 'required|max:255',
            'house' => 'required|numeric',
            'flat' => 'required|numeric',
            'installer' => 'nullable|sometimes|max:255',
            'area' => 'nullable|sometimes|numeric',
            'sum' => 'nullable|sometimes|numeric'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {

            $fin_report = FinReport::findOrFail($id);
            $fin_report->fill($request->all());
            $fin_report->save();
            return redirect('fin-reports')->with('success', 'Отчет изменен!');
        }
    }

}
