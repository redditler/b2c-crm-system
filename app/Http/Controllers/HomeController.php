<?php

namespace App\Http\Controllers;

use App\Action;
use App\ActionProblem;
use App\DailyReport;
use App\Group;
use App\LeedStatus;
use App\Log;
use App\Support\StatisticTable\StatisticTables;
use App\UserGroups;
use App\Regions;
use App\Leed;
use App\UserRegions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Yajra\Datatables\Datatables;
use DB;
use App\UserBranches;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = Regions::all()->pluck('name', 'id')->toArray();
        array_unshift($regions, "Выберите...");
        return view('home', compact('regions'));
    }

    public function promo()
    {
        $regions = Regions::all()->pluck('name', 'id')->toArray();
        array_unshift($regions, "Выберите...");
        return view('promo', compact('regions'));
    }

    public function done()
    {
        $sts_0 = [0 => 'Все'];
        $statuses_all = LeedStatus::select(['id', 'name'])->whereNotIn('id', [5, 6, 10])->get()->pluck('name', 'id')->toArray();
        $statuses = $sts_0 + $statuses_all;
        return view('done', compact('statuses'));
    }

    public function finReport()
    {
        return view('fin-report');
    }

    public function statistics()
    {
        $months = MonthlyPlanController::getMonths();
        array_unshift($months, "Выберите...");
        $branches = UserBranches::all()->pluck('name', 'id')->toArray();
        array_unshift($branches, "Выберите...");
        return view('statistics', compact('months', 'branches'));
    }

    public function updateLeeds(Request $request)
    {
        $leed = Leed::find($request->leed_id);
        $leed->user_id = Auth::user()->id;
        $leed->status_id = $request->status_id;
        $leed->comment = $request->comment;
        $leed->save();
    }

    public function checkUniqueLeedPhone($leed_id, $leed_phone)
    {
        $leed = Leed::where('leed_phone', '=', $leed_phone)->where('id', '<>', $leed_id)->first();
        if ($leed) {
            return true;
        }
        return false;
    }

    public function getHistoryLeeds(Request $request)
    {
        $phones = isset($_POST["phones"]) ? json_decode(html_entity_decode($_POST["phones"])) : NULL;
        if ($phones) {
            $leeds = Leed::select(['created_at', 'leed_name', 'leed_phone', 'user_id', 'leed_region_id'])
                ->whereIn('leed_phone', $phones);

            return Datatables::of($leeds)
                ->addColumn('region', function ($leed) {
                    if (!empty($leed->region_id)) {
                        return $leed->region->name;
                    } else {
                        return '';
                    }
                })
                ->addColumn('manager', function ($leed) {
                    if (!empty($leed->user_id)) {
                        return $leed->manager->name;
                    } else {
                        return '';
                    }
                })
                ->escapeColumns([])
                ->make();
        }

    }

    public function getDoneLeeds(Request $request)
    {
        $date_from = isset($_POST["date_from"]) ? $_POST["date_from"] : NULL;
        $date_to = isset($_POST["date_to"]) ? $_POST["date_to"] : NULL;
        $date_range = isset($_POST["date_range"]) ? $_POST["date_range"] : NULL;
        $status_id = $_POST["status_id"] != 0 ? $_POST["status_id"] : NULL;

//        dd($date_to);

        $statuses = LeedStatus::select(['id', 'name'])->whereNotIn('id', [5, 6, 10])->get();
        if (Auth::user()->analyst) {
            $leeds = Leed::select(['id', 'created_at', 'leed_name', 'leed_phone', 'user_id', 'status_id', 'comment', 'leed_region_id'])//                ->whereNotIn('status_id', [5,6])
            ;
        } else {
            $user_regions = Auth::user()->regions()->get()->pluck('region_id')->toArray();
            $leeds = Leed::select(['id', 'created_at', 'leed_name', 'leed_phone', 'user_id', 'status_id', 'comment', 'leed_region_id'])
//                ->whereNotIn('status_id', [5,6])
                ->whereIn('leed_region_id', $user_regions);
        }

        if ($status_id) {
            $leeds = $leeds->where('status_id', '=', $status_id);
        } else {
            $leeds = $leeds->whereNotIn('status_id', [5, 6, 10]);
        }

        if (!empty($date_from) && !empty($date_to)) {
            $leeds = $leeds->whereBetween('created_at', [$date_from, $date_to]);
        } elseif (!empty($date_from)) {
            $leeds = $leeds->where('created_at', '>=', $date_from);
        } elseif (!empty($date_to)) {
            $leeds = $leeds->where('created_at', '<=', $date_to);
        }

        if (!empty($date_range) && $date_range == 'year') {
            $leeds = $leeds->whereYear('created_at', '=', date('Y'));
        } elseif (!empty($date_range) && $date_range == 'month') {
            $leeds = $leeds->whereMonth('created_at', '=', date('m'));
        } elseif (!empty($date_range) && $date_range == 'day') {
            $leeds = $leeds->where('created_at', '>=', date('Y-m-d'));
        }

//        var_dump($leeds->toSql());

        return Datatables::of($leeds)
            ->addColumn('region', function ($leed) {
                return $leed->region->name;
            })
            ->addColumn('managers', function ($leed) {
                return $leed->manager->name;
            })
            ->filterColumn('comment', function ($query, $keyword) {
                $sql = "comment  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->addColumn('statuses', function ($leed) use ($statuses) {
                $option = '';
                foreach ($statuses AS $staus) {
                    if ($leed->status_id == $staus->id) {
                        $option .= '<option value="' . $staus->id . '" selected>' . $staus->name . '</option>';
                    } else {
                        $option .= '<option value="' . $staus->id . '">' . $staus->name . '</option>';
                    }

                }
                $select = '<select class="leed_status_select" name="" id="leed_status_' . $leed->id . '">' . $option . '</select>';
                if (Auth::user()->manager) {
                    return $select;
                } else {
                    return $leed->status->name;
                }

            })
//            ->addColumn('statuses', function ($leed) use ($statuses) {
//                return $leed->status->name;
//            })
            ->editColumn('leed_phone', function ($leed) {
                if ($this->checkUniqueLeedPhone($leed->id, $leed->leed_phone)) {
                    return '<a href="/contacts/history/' . $leed->id . '" target="_blank" class="leed_danger btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Номер уже отправлял заявку">' . $leed->leed_phone . '</a>';
                }
                return $leed->leed_phone;

            })
            ->editColumn('comment', function ($leed) {
                if (Auth::user()->manager) {
                    return "<input class='leed_cooment' type=\"text\" id=\"leed_comment_" . $leed->id . "\" name=\"comment\" value=\"" . htmlspecialchars($leed->comment) . "\" style=\"width: 100%;\">";
                } else {
                    return $leed->comment;
                }
            })
            ->addColumn('btns', function () {
                if (Auth::user()->manager) {
                    return '<button style=\'border: 1px solid green\' id=\'animate-conteiner\' class=\'btn btn-secondary leed_btn\' name=\'changeFieldProp\'><i style=\'color:green\' class=\'fa fa-check\' aria-hidden=\'true\'></i></button>';
                }
            })
            ->escapeColumns([])
            ->make();
    }

    public function getLeeds(Request $request)
    {
        $statuses = LeedStatus::select(['id', 'name'])->whereNotIn('id', [10])->get();
        if (!Auth::user()->manager) {
            $leeds = Leed::select(['id', 'created_at', 'leed_name', 'leed_phone', 'user_id', 'status_id', 'comment', 'leed_region_id'])
                ->whereIn('status_id', [5, 6]);
            $region_id = $request->region_id;

            if ($region_id != 0) {
                $leeds = $leeds->where('leed_region_id', '=', $region_id);
            }

        } else {
            $user_regions = Auth::user()->regions()->get()->pluck('region_id')->toArray();
            $leeds = Leed::select(['id', 'created_at', 'leed_name', 'leed_phone', 'user_id', 'status_id', 'comment', 'leed_region_id'])
                ->whereIn('status_id', [5, 6])
                ->whereIn('leed_region_id', $user_regions);
        }
        return Datatables::of($leeds)
            ->addColumn('region', function ($leed) {
                return $leed->region->name;
            })
            ->filterColumn('comment', function ($query, $keyword) {
                $sql = "comment  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->addColumn('statuses', function ($leed) use ($statuses) {
                $option = '';
                foreach ($statuses AS $staus) {
                    if ($leed->status_id == $staus->id) {
                        $option .= '<option value="' . $staus->id . '" selected>' . $staus->name . '</option>';
                    } else {
                        $option .= '<option value="' . $staus->id . '">' . $staus->name . '</option>';
                    }

                }
                $select = '<select class="leed_status_select" name="" id="leed_status_' . $leed->id . '">' . $option . '</select>';
                if (Auth::user()->manager) {
                    return $select;
                } else {
                    return $leed->status->name;
                }

            })
            ->addColumn('comment', function ($leed) {
                if (Auth::user()->manager) {
                    return "<input class='leed_cooment' type=\"text\" id=\"leed_comment_" . $leed->id . "\" name=\"comment\" value=\"" . htmlspecialchars($leed->comment) . "\" style=\"width: 100%;\">";
                } else {
                    return $leed->comment;
                }
            })
            ->addColumn('btns', function () {
                if (Auth::user()->manager) {
                    return '<button style=\'border: 1px solid green\' id=\'animate-conteiner\' class=\'btn btn-secondary leed_btn\' name=\'changeFieldProp\'><i style=\'color:green\' class=\'fa fa-check\' aria-hidden=\'true\'></i></button>';
                }
            })
            ->editColumn('leed_phone', function ($leed) {
                if ($this->checkUniqueLeedPhone($leed->id, $leed->leed_phone)) {
                    return '<a href="/contacts/history/' . $leed->id . '" target="_blank" class="btn btn-danger btn-xs leed_danger" data-toggle="tooltip" data-placement="bottom" title="Номер уже отправлял заявку">' . $leed->leed_phone . '</a>';
                }
                return $leed->leed_phone;

            })
            ->escapeColumns([])
            ->make();
    }

    public function getPromoLeeds(Request $request)
    {
        $statuses = LeedStatus::select(['id', 'name'])->get();

        $user_regions = Auth::user()->regions()->get()->pluck('region_id')->toArray();

        $leeds = Leed::select(['id', 'created_at', 'leed_name', 'leed_phone', 'user_id', 'status_id', 'comment', 'leed_region_id'])
            ->whereIn('status_id', [10]);

        return Datatables::of($leeds)
            ->addColumn('region', function ($leed) {
                return $leed->region->name;
            })
//            ->addColumn('managers', function ($leed) use ($users) {
//                $option = '';
//
//                if($leed->user_id == 0){
//                    $option .= '<option value="0" selected>Не назначен</option>';
//                }
//
//                foreach ($users AS $user){
//                    if($leed->user_id == $user->id){
//                        $option .= '<option value="'.$user->id.'" selected>'.$user->name.'</option>';
//                    }
//                    else{
//                        $option .= '<option value="'.$user->id.'">'.$user->name.'</option>';
//                    }
//                }
//
//                $select = '<select name="" id="leed_manager_'.$leed->id.'">'.$option.'</select>';
//                return $select;
//            })
            ->filterColumn('comment', function ($query, $keyword) {
                $sql = "comment  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->addColumn('statuses', function ($leed) use ($statuses) {
                $option = '';
                foreach ($statuses AS $staus) {
                    if ($leed->status_id == $staus->id) {
                        $option .= '<option value="' . $staus->id . '" selected>' . $staus->name . '</option>';
                    } else {
                        $option .= '<option value="' . $staus->id . '">' . $staus->name . '</option>';
                    }

                }
                $select = '<select class="leed_status_select" name="" id="leed_status_' . $leed->id . '">' . $option . '</select>';
                if (Auth::user()->manager) {
                    return $select;
                } else {
                    return $leed->status->name;
                }

            })
            ->addColumn('comment', function ($leed) {
                if (Auth::user()->manager) {
                    return "<input class='leed_cooment' type=\"text\" id=\"leed_comment_" . $leed->id . "\" name=\"comment\" value=\"" . htmlspecialchars($leed->comment) . "\" style=\"width: 100%;\">";
                } else {
                    return $leed->comment;
                }
            })
            ->addColumn('btns', function () {
                if (Auth::user()->manager) {
                    return '<button style=\'border: 1px solid green\' id=\'animate-conteiner\' class=\'btn btn-secondary leed_btn\' name=\'changeFieldProp\'><i style=\'color:green\' class=\'fa fa-check\' aria-hidden=\'true\'></i></button>';
                }
            })
            ->editColumn('leed_phone', function ($leed) {
                if ($this->checkUniqueLeedPhone($leed->id, $leed->leed_phone)) {

                    return '<a href="/contacts/history/' . $leed->id . '" target="_blank" class="btn btn-danger btn-xs leed_danger" data-toggle="tooltip" data-placement="bottom" title="Номер уже отправлял заявку">' . $leed->leed_phone . '</a>';
                }
                return $leed->leed_phone;

            })
            ->escapeColumns([])
            ->make();
    }

    public function statisticstest()
    {
        $months = MonthlyPlanController::getMonths();
        array_unshift($months, "Выберите...");
        $branches = UserBranches::all()->pluck('name', 'id')->toArray();
        array_unshift($branches, "Выберите...");

        return view('statisticstest', compact('months', 'branches'));
    }

    public function getLeedsPeriod(Request $request)
    {

        return true;
    }

    public function statisticsNew()
    {
        $dateNow = Carbon::now();
        $managerStatic['period'] = Carbon::now()->format('j');
        $managerStatic['user'] = Auth::user();
        $managerStatic['branch'] = UserBranches::find(Auth::user()->branch_id);
        $managerStatic['region'] = UserRegions::where('user_id', $managerStatic['user']->id)->first()->region_id;
        $managerStatic['daily_reports'] = DailyReport::where('user_id', $managerStatic['user']->id)->get();

        $startMonth = Carbon::make('01-' . Carbon::now()->format('m-Y') . ' 00:00:00')->timestamp;
        $endMonth = Carbon::make(Carbon::now()->format('t-m-Y') . ' 23:59:59')->timestamp;

        /*---------------------START LEED---------------------*/
        $leedAll = Leed::where('leed_region_id', $managerStatic['region'])->get();

        $leedMotn = [];
        foreach ($leedAll as $leed) {
            if ((Carbon::make($leed->created_at)->timestamp >= $startMonth) && (Carbon::make($leed->created_at)->timestamp <= $endMonth)) {
                $leedMotn[] = $leed;
            }
        }
        $leeDay = [];
        for ($i = 1; $i <= $managerStatic['period']; $i++) {
            foreach ($leedMotn as $leed) {
                if ((Carbon::make($leed->created_at)->timestamp >= Carbon::make($dateNow->format('Y-m') . '-' . $i . ' 00:00:00')->timestamp) &&
                    (Carbon::make($leed->created_at)->timestamp <= Carbon::make($dateNow->format('Y-m') . '-' . $i . ' 23:59:59')->timestamp)) {
                    $leeDay[$i][] = $leed;
                }
            }
        }
        $leedUser = [];

        foreach ($leeDay as $key => $value) {
            foreach ($value as $val) {
                if ($val->user_id == $managerStatic['user']->id) {
                    $leedUser[$key][] = $value;
                }
            }
        }

        $managerStatic['leeDay'] = $leeDay;
        $managerStatic['leedUser'] = $leedUser;

        /*---------------------START CALLS---------------------*/
        $reportMonth = [];
        foreach ($managerStatic['daily_reports'] as $report) {
            if ((Carbon::make($report->date)->timestamp >= $startMonth) && (Carbon::make($report->date)->timestamp <= $endMonth)) {
                $reportMonth[] = $report;
            }
        }

        $reportDay = [];
        for ($i = 1; $i <= $managerStatic['period']; $i++) {
            foreach ($reportMonth as $report) {
                if (Carbon::make($report->date)->day == Carbon::make('2018-' . $dateNow->format('m') . '-' . $i)->day) {
                    $reportDay[$i] = $report;
                }
            }
        }
        $managerStatic['reportDay'] = $reportDay;

        return view('managerReports.statisticsNew', ['managerStatic' => $managerStatic]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function statisticsChief()
    {

        return view('managerReports.statisticsChief');
    }

    public function statisticsDate(Request $request)
    {
        return StatisticTables::statisticChief($request);
    }

    public function statisticsSumDate(Request $request)
    {
        return DailyReport::statisticsSumDate($request);
    }



    public function statisticsRmShow()
    {
        $data = DailyReport::statistic(false);

        return view('managerReports.statisticsRM', [
            'branches' => json_encode($data['branches']),
            'tableDays' => json_encode($data['tableDays']),
            'tableBranches' => json_encode($data['tableBranches']),
        ]);
    }

    public function statisticsRm(Request $request)
    {
        $date['dateFrom'] = $request->dateFrom;
        $date['dateTo'] = $request->dateTo;

        $data = DailyReport::statistic($date);

        return response()->json($data);
    }

    public function getAllGroup(Request $request)
    {
        return UserGroups::all();
    }
}
