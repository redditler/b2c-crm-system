<?php

namespace App\Http\Controllers;

use App\Leed;
use App\Support\Colors;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LeadPromoController extends Controller
{
    public function index()
    {
        if (Auth::user()->role_id == 5){
            return redirect('/');
        }
        return view('leadsPromo.leadsPromo');
    }

    public function indexLeadsPromo(Request $request)
    {
        $user = Auth::user();

        $filer = $request->all();

        $leadPeriodDate = [];
        $dateFromLead = Leed::dateFromLead();

        if(empty(session('leadDateFrom'))){
            $leadPeriodDate['leadDateFrom'] = isset($filer['leadDateFrom']) ? Carbon::make($filer['leadDateFrom'] . ' 00:00:00')->format('Y-m-d H:i:s') : $dateFromLead[0];
            session(['leadDateFrom' => $leadPeriodDate['leadDateFrom']]);
        } else {
            $leadPeriodDate['leadDateFrom'] = isset($filer['leadDateFrom']) ? Carbon::make($filer['leadDateFrom'] . ' 00:00:00')->format('Y-m-d H:i:s') : session('leadDateFrom');
            session(['leadDateFrom' => $leadPeriodDate['leadDateFrom']]);
        }
        if(empty(session('leadDateTo'))){
            $leadPeriodDate['leadDateTo'] = isset($filer['leadDateTo']) ? Carbon::make($filer['leadDateTo'] . ' 23:59:59')->format('Y-m-d H:i:s') : $dateFromLead[1];
            session(['leadDateTo' => $leadPeriodDate['leadDateTo']]);
        } else {
            $leadPeriodDate['leadDateTo'] = isset($filer['leadDateTo']) ? Carbon::make($filer['leadDateTo'] . ' 23:59:59')->format('Y-m-d H:i:s') : session('leadDateTo');
            session(['leadDateTo' => $leadPeriodDate['leadDateTo']]);
        }

        $color = Colors::colorLeadStatus();

        $leadStatusId = LaedsConrroler::processLeadStatusId($request);

        if(!empty($filer['search']['value'])) {
            $leads = Leed::searchLeadByPhone($filer['search']['value']);
        } else {
            $leads = Leed::leadPromoFromDataTables($user, $leadPeriodDate, $request, $leadStatusId);
        }


        return Datatables::of($leads)
            ->orderColumn('created_at', 'created_at $1')
            ->addColumn('created_at', function ($leads) {
                return $leads->created_at;
            })
            ->orderColumn('region', 'leeds.leed_region_id $1')
            ->addColumn('region', function ($leads) {
                return $leads->manager->branch->name.'<br/>'.$leads->manager->group->name;
            })
            ->orderColumn('leed_name', 'leed_name $1')
            ->addColumn('leed_name', function ($leads) {
                return $leads->contact_id ?
                    '<a href="/contact/' . $leads->contact_id . '/edit" class="btn btn-info" target="_blank">' . $leads->contact->fio . '</a>'
                    . (Leed::getOneClientLeadCount($leads->contact_id) >= 2 ? '<button class="btn btn-sm btn-default numberOfLeadsPerClient" value="' . $leads->contact_id . '">'
                        . Leed::getOneClientLeadCount($leads->contact_id) . '</button>' : '')
                    : $leads->leed_name;;
            })
            ->orderColumn('leed_phone', 'leed_phone $1')
            ->addColumn('leed_phone', function ($leads) {
                return $leads->leed_phone;
            })
            ->addColumn('promo_code', function ($leads) {
                return $leads->promo->promo_code;
            })
//            ->addColumn('promo_discount', function ($leads) {
//                return $leads->promo->promo_discount . ' %';
//            })
//            ->orderColumn('status', 'leeds.status_id $1')
//            ->addColumn('status', function ($leads) use ($user, $color) {
//                if ($leads->status_id == 11) {
//                    $leadStatusIdColor = '<div class="progress-column progress-2"></div>';
//                } else if ($leads->status_id == 12) {
//                    $leadStatusIdColor = '<div class="progress-column progress-3"></div>';
//                } else if ($leads->status_id == 13) {
//                    $leadStatusIdColor = '<div class="progress-column progress-4"></div>';
//                } else if ($leads->status_id == 14) {
//                    $leadStatusIdColor = '<div class="progress-column progress-5"></div>';
//                } else if ($leads->status_id == 15) {
//                    $leadStatusIdColor = '<div class="progress-column progress-6"></div>';
//                } else {
//                    $leadStatusIdColor = '<div class="progress-column progress-1"></div>';
//                }
//
//
//                if ($user->role_id != 3) {
//                    return $leads->status->name . $leadStatusIdColor;
//                } else {
//                    if ($leads->rejected_lead == 0) {
//                        $leadSelect = '<select value="' . $leads->status_id . '" class="form-control" form="form_' . $leads->id . '" name="status_id">
//                                            <option selected value="' . $leads->status_id . '">' . $leads->status->name . '</option>
//                                            <option  value="11">В обработке</option>
//                                            <option  value="12">Замер</option>
//                                            <option  value="13">Предложение</option>
//                                            <option  value="14">Выставлен счет</option>
//                                            <option  value="15">Оплачен</option>
//                                        </select>';
//                        return $leadSelect . $leadStatusIdColor;
//                    } else {
//                        return $leads->status->name . $leadStatusIdColor;
//                    }
//                }
//            })
            ->addColumn('manager', function ($leads) {
                return ((isset($leads->manager->name)) ? $leads->manager->name : 'Не распределен');
            })
//            ->addColumn('managerCall', function ($leads) {
//                return (isset($leads->managerCall->name)) ? $leads->managerCall->name : '';
//            })
//            ->addColumn('comment', function ($leads) use ($user){
//                if ($user->role_id == 3) {
//                    if ($leads->rejected_lead == 0) {
//                        return '<input type="text" form="form_'.$leads->id.'" class="form-control" name="comment" value="' . $leads->comment. '">';
//                    }else{
//                        return $leads->comment;
//                    }
//                }else{
//                    return $leads->comment;
//                }
//            })
//            ->addColumn('btns', function ($leads) use ($user) {
//                if ($user->role_id == 3) {
//                    if ($leads->rejected_lead == 0) {
//                        return '<form id="form_' . $leads->id . '" method="post" action="/updateLead">' . csrf_field() . '<input class="idLead" type="hidden" name="id" value="' . $leads->id . '">
//                         <input type="submit" value="Изменить" class="btn btn-success forEdit"></form>';
//                    } else {
//                        return '<button class="btn btn-success" disabled>Изменить</button>';
//                    }
//
//                }
//            })
//            ->addColumn('reject', function ($leads) use ($user) {
//                if ($user->role_id == 3) {
//                    if ($leads->rejected_lead == 0) {
//                        return '<form method="post" action=' . route('rejectTrue') . '>' . csrf_field() . '<input type="hidden" name="id" value="' . $leads->id . '"><input type="submit" class="btn btn-danger" value="X"></form>';
//                    } else {
//                        return '<button class="btn btn-danger" disabled>X</button>';
//                    }
//                }elseif ($user->role_id <= 2){
//                    if ($leads->rejected_lead == 1) {
//                        return '<form method="post" action=' . route('rejectFalse') . '>' . csrf_field() . '<input type="hidden" name="id" value="' . $leads->id . '"><input type="submit" class="btn btn-info" value="+"></form>';
//                    } else {
//                        return '<button class="btn btn-info" disabled>+</button>';
//                    }
//                }
//            })
            ->escapeColumns([])
            ->make(true);

    }
}
