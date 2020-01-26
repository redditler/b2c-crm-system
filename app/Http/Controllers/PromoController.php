<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Promo;
use App\Leed;
use App\LeedStatus;
use Auth;
use DB;
use Yajra\Datatables\Datatables;

class PromoController extends Controller
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

    public function checkUniqueLeedPhone($leed_id, $leed_phone)
    {
        $leed = Leed::where('leed_phone', '=', $leed_phone)->where('id', '<>', $leed_id)->first();
        if ($leed) {
            return true;
        }
        return false;
    }

    public function getPromoLeeds(Request $request)
    {
        $statuses = LeedStatus::select(['id', 'name'])->get();
        $user_regions = Auth::user()->regions()->get()->pluck('region_id')->toArray();

        $promos = Promo::select(
            ['promos.*',
                'leeds.id as lid',
                'leeds.leed_name',
                'leeds.leed_phone',
                'leeds.comment',
                'regions.name as region_name',
                'leed_statuses.name as status_name',
                'leeds.status_id as status_id',
            ])
            ->leftJoin("leeds", "promos.leed_id", "=", "leeds.id")
            ->leftJoin("regions", "leeds.leed_region_id", "=", "regions.id")
            ->leftJoin("leed_statuses", "leeds.status_id", "=", "leed_statuses.id");
//		->orderBy("leeds.status_id",'desc');

        $region_id = $request->region_id;

        if ($region_id != 0) {
            $promos = $promos->where('leeds.leed_region_id', '=', $region_id);
        }

        return Datatables::of($promos)
            ->filter(function ($promo) use ($request) {
                if (!is_null($request->ps_name)) {
                    $promo->where('leeds.leed_name', 'like', "%{$request->ps_name}%");
                }
                if (!is_null($request->ps_phone)) {
                    $promo->where('leeds.leed_phone', 'like', "%{$request->ps_phone}%");
                }
                if (!is_null($request->ps_email)) {
                    $promo->where('promos.promo_email', 'like', "%{$request->ps_email}%");
                }
                if (!is_null($request->ps_promo_code)) {
                    $promo->where('promos.promo_code', 'like', "%{$request->ps_promo_code}%");
                }
                if (!is_null($request->ps_promo_discount)) {
                    $promo->where('promos.promo_discount', 'like', "%{$request->ps_promo_discount}%");
                }
            })
            ->addColumn('region', function ($promo) {
                return $promo->region_name;
            })
            ->addColumn('statuses', function ($promo) use ($statuses) {
                $option = '';
                foreach ($statuses AS $staus) {
                    if ($promo->status_id == $staus->id) {
                        $option .= '<option value="' . $staus->id . '" selected>' . $staus->name . '</option>';
                    } else {
                        $option .= '<option value="' . $staus->id . '">' . $staus->name . '</option>';
                    }
                }
                $disabled = $this->_disabledFields($promo->status_id);
                $select = '<select class="leed_status_select" 
						   name="" 
						   id="leed_status_' . $promo->leed_id . '"
						   ' . $disabled . '>' . $option . '</select>';
                if (Auth::user()->manager) {
                    return $select;
                } else {
                    return $promo->status_name;
                }
            })
            ->addColumn('comment', function ($promo) {
                if (Auth::user()->manager) {
                    $disabled = $this->_disabledFields($promo->status_id);
                    return "<input class='leed_cooment' 
						 type='text' 
						 id='leed_comment_" . $promo->leed_id . "'
						 name='comment' value='" . htmlspecialchars($promo->comment) . "' 
						 style='width: 100%;'
						 " . $disabled . ">";
                } else {
                    return $promo->comment;
                }
            })
            ->addColumn('btns', function ($promo) {
                if (Auth::user()->manager) {
                    $disabled = $this->_disabledFields($promo->status_id);
                    return "<button style='border: 1px solid green' 
						  id='animate-conteiner' 
						  class='btn btn-secondary leed_btn' 
						  name='changeFieldProp'
						  " . $disabled . ">
				  	<i style='color:green' class='fa fa-check' aria-hidden='true'></i>
				  </button>";
                }
            })
            ->editColumn('leed_phone', function ($promo) {
                if ($this->checkUniqueLeedPhone($promo->leed_id, $promo->leed_phone)) {
                    return "<a href='/contacts/history/{$promo->lid}''
		    target='_blank' class='btn btn-danger btn-xs leed_danger''
		    data-toggle='tooltip'
		    data-placement='bottom'
		    title='Номер уже отправлял заявку'>{$promo->leed_phone}</a>";
                }
                return $promo->leed_phone;
            })
            ->escapeColumns([])
            ->make();
    }

    /**
     * Disables fields that aren't in the array
     * @param integer $leed_status
     * @return string
     */
    private function _disabledFields(int $leed_status)
    {
        $enabled_array = [10];
        return (!in_array($leed_status, $enabled_array))
            ? 'disabled'
            : '';
    }
}
