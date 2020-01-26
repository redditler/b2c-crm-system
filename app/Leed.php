<?php

namespace App;

use App\Http\Controllers\LeedController;
use App\Support\Colors;
use App\Support\LeadFilter\LeadFilterRender;
use App\Support\UserRole\SelectRole;
use App\Support\UserRole\UserRoleRegionManager;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class Leed extends Model
{
    protected $fillable = [
        'id', 'leed_name', 'leed_phone', 'leed_region_id', 'status_id', 'user_id', 'label_id', 'comment', 'created_at'
    ];

    public static function dateFromLead()
    {
        return [Carbon::make(Carbon::now()->format('Y-m') . '-01 00:00:00')->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')];
    }

    public function leedIp()
    {
        return $this->hasOne('App\LeedIps', 'id', 'leed_id');
    }

    public function status()
    {
        return $this->hasOne('App\LeedStatus', 'id', 'status_id');
    }

    public function manager()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function managerCall()
    {
        return $this->hasOne('App\User', 'id', 'cm_id');
    }

    public function region()
    {
        return $this->hasOne('App\Regions', 'id', 'leed_region_id');
    }

    public function promo()
    {
        return $this->hasOne('App\Promo', 'leed_id', 'id');
    }

    public function leadReceive()
    {
        return $this->hasOne('App\LeedReceive', 'id', 'leed_receive_id');
    }

    public function leadType()
    {
        return $this->hasOne('App\LeedType', 'id', 'leed_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * обратная связь с таблицей contacts
     */
    public function contact()
    {
        return $this->belongsTo('App\ContactNew', 'contact_id', 'id');
    }


    /**
     * @param $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Создаем новый лид
     */
    public static function insertNewLead($request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'leed_name' => 'required',
            'leed_phone' => 'required|size:10|regex:/[0-9]/',
            'leed_region_id' => 'required',
            'label_id' => 'required',

        ], [
            'leed_name.required' => 'Заполните ячейку "Имя"',
            'leed_phone.required' => 'Заполните ячейку "Телефон"',
            'leed_phone.size' => 'Телефонный номер должен состоять из 10 цифр (0998887766)',
            'leed_phone.regex' => 'Телефонный номер должен состоять только из цифр',
            'leed_region_id.required' => 'Выберите регион',
            'label_id.required' => 'Выберите тип заявки',
        ]);

        if ($validator->fails()) {

            return response($validator->errors()->all());
        }

        DB::transaction(function () use ($request, $user) {
            $contact = ContactNew::query()->where('id', ContactPhones::getIdWithPhone($request->leed_phone))->first();
            $contactPhone = $contact ? ContactPhones::getPrimaryPhone($contact->id) : null;

            if ($contact) {
                ContactHistory::create([
                    'client_id' => $contact->id,
                    'user_id' => $user->id,
                    'description' => 'Повторное обращение клиента!
                    ']);


                $lead = new Leed();
                $lead->leed_name = $contact->fio;
                $lead->leed_phone = $contactPhone->phone;
                $lead->contact_id = $contact->id;
                $lead->leed_region_id = $contact->region_id ? $contact->region_id : $request->leed_region_id;
                $lead->label_id = $request->label_id;

                if ($user->role_id == 5) {
                    $lead->user_id = $contact->user_id ? $contact->user_id : 0;
                    $lead->leed_receive_id = 2;
                    $lead->cm_id = $user->id;
                    $lead->cm_comment = $request->cm_comment;
                    $lead->comment = $request->cm_comment;

                } elseif ($user->role_id == 3) {
                    $lead->user_id = $user->id;
                    $lead->leed_receive_id = 3;
                    $lead->comment = $request->comment;

                } else {
                    $lead->user_id = $contact->user_id ? $contact->user_id : 0;
                    $lead->leed_receive_id = 1;
                }

                $lead->status_id = 5;
                $lead->save();

            } else {

                $newContact = ContactNew::create([
                    'fio' => $request->leed_name,
                    'region_id' => $request->leed_region_id,
                    'user_id' => $user->role_id == 5 ? 0 : $user->role_id == 3 ? $user->id : 0,
                    'group_id' => $user->group_id == 4 ? 4 : ($request->leed_region_id == 13 ? 1 : 2),
                ]);


                ContactHistory::create([
                    'client_id' => $newContact->id,
                    'user_id' => $user->id,
                    'description' => 'Контакт создан ' . $user->name . ', ID: ' . $newContact->id . ' / ' . $request->leed_name,
                ]);

                ContactPhones::create([
                    'contact_id' => $newContact->id,
                    'phone' => $request->leed_phone,
                    'primary' => 1
                ]);

                $lead = new Leed();
                $lead->leed_name = $request->leed_name;
                $lead->leed_phone = $request->leed_phone;
                $lead->contact_id = $newContact->id;
                $lead->leed_region_id = $request->leed_region_id;
                $lead->label_id = $request->label_id;

                if ($user->role_id == 5) {
                    $lead->user_id = 0;
                    $lead->leed_receive_id = 2;
                    $lead->cm_id = $user->id;
                    $lead->cm_comment = $request->cm_comment;
                    $lead->comment = $request->cm_comment;

                } elseif ($user->role_id == 3) {
                    $lead->user_id = $user->id;
                    $lead->leed_receive_id = 3;
                    $lead->comment = $request->comment;

                } else {
                    $lead->user_id = 0;
                    $lead->leed_receive_id = 1;
                }

                $lead->status_id = 5;
                $lead->save();

            }
        });

        return response()->make('Lead added!');

    }

    /**
     * @param int $rejected_lead 0 - normal(work lead, default), 1 - canceled lead
     * @return int
     */
    public static function getLeadAll($rejected_lead = 0)
    {
        $user = Auth::user();
        $children = SelectRole::selectRole($user);

        return self::query()
            ->whereRaw("(`contact_id`,`id`) IN (SELECT `contact_id`, MAX(id) FROM leeds GROUP BY `contact_id`)")
            ->whereBetween('created_at', self::dateFromLead())
            //->where('leed_type_id', 1)
            ->where('rejected_lead', $rejected_lead)
            ->where(function ($q) use ($children, $user) {
                foreach ($children->getUserChildren() as $child) {

                    $q->orWhere('user_id', $child->id);
                }
                if ($user->group_id == 1 || $user->group_id == 2 || $user->group_id == 3) {
                    foreach ($children->getUserRegion() as $region) {
                        $q->orWhere('user_id', 0)->where('leed_region_id', $region->id);
                    }
                }
            })
            ->count();
    }


    /**
     * @param int $rejected_lead 0 - normal(work lead, default), 1 - canceled lead
     * @return int
     */
    public static function getLeadNew($rejected_lead = 0)
    {
        $userRegions = UserRegions::query()
            ->where('user_id', Auth::user()->id)->get();
        $managers = User::userManager();
        return self::query()
            ->whereBetween('created_at', self::dateFromLead())
            ->where('leed_type_id', 1)
            ->where('rejected_lead', $rejected_lead)
            ->where('status_id', 5)
            ->where(function ($q) use ($userRegions) {
                foreach ($userRegions as $userRegion) {
                    $q->orWhere('leed_region_id', $userRegion->region_id);
                }
            })
            ->where(function ($q) use ($managers) {
                foreach ($managers as $value) {
                    $q->orWhere('user_id', $value['id']);
                }
                $q->orWhere('user_id', 0);
            })
            ->count();
    }


    /**
     * @param $stat - use lead status
     * @param int $rejected_lead 0 - normal(work lead, default), 1 - canceled lead
     * @return int
     */
    public static function getLeadStat($stat, $rejected_lead = 0)
    {
        $user = Auth::user();
        $userRegions = UserRegions::query()
            ->where('user_id', $user->id)->get();
        $managers = User::userManager();

        return self::query()
            ->whereBetween('created_at', self::dateFromLead())
            ->where('leed_type_id', 1)
            ->where('rejected_lead', $rejected_lead)
            ->where('status_id', $stat)
            ->where(function ($q) use ($userRegions) {
                foreach ($userRegions as $userRegion) {
                    $q->orWhere('leed_region_id', $userRegion->region_id);
                }
            })
            ->where(function ($q) use ($managers, $user) {
                if ($user->role_id == 3) {
                    $q->orWhere('user_id', $user->id);
                } else {
                    foreach ($managers as $value) {
                        $q->orWhere('user_id', $value['id']);
                    }
                    $q->orWhere('user_id', 0);
                }
            })
            ->count();
    }


    /**
     * @param $user - Auth user
     * @param $leadPeriodDate - period from ... to ...
     * @param $leadRegionId
     * @param $request - filters
     * @param $managers
     * @param $leadStatusId - what status use lead (array)
     * @param $userRegions
     * @param int $rejected_lead 0 - normal(work lead, default), 1 - canceled lead
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function leadAllFromDataTables($user, $leadPeriodDate, $request, $leadStatusId, $rejected_lead = 0)
    {
        $filter = LeadFilterRender::chooseFilterMethod($request) ?? false;
        $children = SelectRole::selectRole($user);
        return Leed::query()
            ->whereRaw("(`contact_id`,`id`) IN (SELECT `contact_id`, MAX(id) FROM leeds GROUP BY `contact_id`)")
            ->whereBetween('created_at', [$leadPeriodDate['leadDateFrom'], $leadPeriodDate['leadDateTo']])
            //->where('leed_type_id', 1)
            ->where(function($query) use ($rejected_lead){
                if(is_array($rejected_lead)){
                    foreach($rejected_lead as $thisRejectCode){
                        $query->orWhere('rejected_lead', $thisRejectCode);
                    }
                }else{
                    $query->where('rejected_lead', $rejected_lead);
                }
            })
            ->where(function ($q) use ($children, $user) {
                foreach ($children->getUserChildren() as $child) {

                    $q->orWhere('user_id', $child->id);
                }
                if ($user->group_id == 1 || $user->group_id == 2 || $user->group_id == 3) {
                    foreach ($children->getUserRegion() as $region) {
                        $q->orWhere('user_id', 0)->where('leed_region_id', $region->id);
                    }
                }
            })
            ->where(function ($q) use ($leadStatusId) {
                if ($leadStatusId) {
                    foreach ($leadStatusId as $value) {
                        $q->orWhere('status_id', $value);
                    }
                }
            })
            ->where(function ($q) use ($filter) {
                if ($filter) {
                    foreach ($filter as $value) {
                        $q->orWhere('user_id', $value->id);//@TODO
                        if ($value->group_id == 1 || $value->group_id == 2){

                            $q->orWhere('user_id', 0)->where('leed_region_id', $value->branch->region_id);
                        }
                    }
                }
            })
            ->orderBy('id', 'desc');
    }

    /**
     * @param $user - Auth user
     * @param $leadPeriodDate - period from ... to ...
     * @param $leadRegionId
     * @param $request - filters
     * @param $managers
     * @param $leadStatusId - what status use lead (array)
     * @param $userRegions
     * @param int $rejected_lead 0 - normal(work lead, default), 1 - canceled lead
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function leadPromoFromDataTables($user, $leadPeriodDate, $request, $leadStatusId, $rejected_lead = 0)
    {
        $filter = LeadFilterRender::chooseFilterMethod($request) ?? false;
        $children = SelectRole::selectRole($user);
        return Leed::query()
            ->whereRaw("(`contact_id`,`id`) IN (SELECT `contact_id`, MAX(id) FROM leeds GROUP BY `contact_id`)")
            ->whereBetween('created_at', [$leadPeriodDate['leadDateFrom'], $leadPeriodDate['leadDateTo']])
            ->where('leed_type_id', 2)
            ->where('rejected_lead', $rejected_lead)
            ->where(function ($q) use ($children, $user) {
                foreach ($children->getUserChildren() as $child) {

                    $q->orWhere('user_id', $child->id);
                }
                if ($user->group_id == 1 || $user->group_id == 2 || $user->group_id == 3) {
                    foreach ($children->getUserRegion() as $region) {
                        $q->orWhere('user_id', 0)->where('leed_region_id', $region->id);
                    }
                }
            })
            ->where(function ($q) use ($leadStatusId) {
                if ($leadStatusId) {
                    foreach ($leadStatusId as $value) {
                        $q->orWhere('status_id', $value);
                    }
                }
            })
            ->where(function ($q) use ($filter) {
                if ($filter) {
                    foreach ($filter as $value) {
                        $q->orWhere('user_id', $value->id);//@TODO
                        if ($value->group_id == 1 || $value->group_id == 2){

                            $q->orWhere('user_id', 0)->where('leed_region_id', $value->branch->region_id);
                        }
                    }
                }
            })
            ->orderBy('id', 'desc');
    }

    /**
     * @param $leads
     * @param $user auth
     * @param $color array
     * @return mixed datatables result
     * @throws \Exception
     */
    public static function dataTablesLead($leads, $user, $color)
    {
        return Datatables::of($leads)
            ->orderColumn('created_at', 'created_at $1')
            ->addColumn('created_at', function ($leads) {
                return $leads->created_at;
            })
            ->orderColumn('leed_receive_id', 'leed_receive_id $1')
            ->addColumn('leed_receive_id', function ($leads) {
                return $leads->leed_receive_id;
            })
            ->orderColumn('region', 'leeds.leed_region_id $1')
            ->addColumn('region', function ($leads) {
                return $leads->region->name;
            })
            ->orderColumn('leed_name', 'leed_name $1')
            ->addColumn('leed_name', function ($leads) {
                return $leads->contact_id ?
                    '<a href="/contact/' . $leads->contact_id . '/edit" class="btn btn-info" target="_blank">' . $leads->contact->fio . '</a>'
                    . (self::getOneClientLeadCount($leads->contact_id) >= 2 ? '<button class="btn btn--badge numberOfLeadsPerClient" value="' . $leads->contact_id . '">'
                        . self::getOneClientLeadCount($leads->contact_id) . '</button>' : '')
                    : $leads->leed_name;
            })
            ->orderColumn('leed_phone', 'leed_phone $1')
            ->addColumn('leed_phone', function ($leads) {

                return ContactPhones::query()->where('contact_id', $leads->contact_id)->where('primary', 1)->first()
                    ? ContactPhones::query()->where('contact_id', $leads->contact_id)->where('primary', 1)->first()->phone
                    : $leads->leed_phone;
            })
            ->orderColumn('status', 'leeds.status_id $1')
            ->addColumn('status', function ($leads) use ($user, $color) {
                if ($leads->status_id == 11) {
                    $leadStatusIdColor = '<div class="progress-column progress-2"></div>';
                } else if ($leads->status_id == 12) {
                    $leadStatusIdColor = '<div class="progress-column progress-3"></div>';
                } else if ($leads->status_id == 13) {
                    $leadStatusIdColor = '<div class="progress-column progress-4"></div>';
                } else if ($leads->status_id == 14) {
                    $leadStatusIdColor = '<div class="progress-column progress-5"></div>';
                } else if ($leads->status_id == 15) {
                    $leadStatusIdColor = '<div class="progress-column progress-6"></div>';
                } else {
                    $leadStatusIdColor = '<div class="progress-column progress-1"></div>';
                }


                if ($user->role_id != 3) {
                    return '<div class="status-title">' . $leads->status->name . '</div>'. $leadStatusIdColor;
                } else {
                    if ($leads->rejected_lead == 0) {
                        $leadSelect = '<select value="' . $leads->status_id . '" class="form-control" form="form_' . $leads->id . '" name="status_id">
                                            <option selected value="' . $leads->status_id . '">' . $leads->status->name . '</option>
                                            <option  value="11">В обработке</option>
                                            <option  value="12">Замер</option>
                                            <option  value="13">Предложение</option>
                                            <option  value="14">Выставлен счет</option>
                                            <option  value="15">Оплачен</option>
                                        </select>';
                        return $leadSelect . $leadStatusIdColor;
                    } else {
                        return '<div class="status-title">' . $leads->status->name . '</div>'. $leadStatusIdColor;
                    }
                }
            })
            ->addColumn('manager', function ($leads) {
                return ((isset($leads->manager->name)) ? $leads->manager->name : 'Не распределен');
            })
            ->addColumn('managerCall', function ($leads) {
                return (isset($leads->managerCall->name)) ? $leads->managerCall->name : '';
            })
            ->addColumn('reject', function ($leads) use ($user) {
                if (!($user->role_id == 3 || $user->role_id == 5)) {
                    if ($leads->rejected_lead == 1) {
                        return '<form method="post" action=' . route('rejectFalse') . '>' . csrf_field() . '<input type="hidden" name="id" value="' . $leads->id . '"><input type="submit" class="btn btn-info" value="+"></form>';
                    }
                }
            })
            ->addColumn('comment', function ($leads) use ($user) {
                if ($user->role_id == 3) {
                    if ($leads->rejected_lead == 0) {
                        return '<textarea type="text" form="form_' . $leads->id . '" class="form-control table_comment" name="comment" >' . $leads->comment . '</textarea>';
                    } else {
                        return $leads->comment;
                    }
                } else {
                    return $leads->comment;
                }
            })
            ->addColumn('btns', function ($leads) use ($user) {
                if ($user->role_id == 3) {
                    if ($leads->rejected_lead == 0) {
                        return '<form id="form_' . $leads->id . '" class="update-lead-form" method="post" action="/updateLead">' . csrf_field() . '<input class="idLead" type="hidden" name="id" value="' . $leads->id . '">
                         <input type="submit" value="Подтвердить изменения" class="btn btn_agree forEdit"></form>';
                    } else {
                        return '<button class="btn btn_agree" disabled>Подтвердить изменения </button>';
                    }
                }
            })
            ->addColumn('btnDefect', function ($leads) use ($user) {
                if ($user->role_id == 3) {
                    if ($leads->rejected_lead == 0) {
                        return '<form method="post" class="disabledFormLead" action=' . route('rejectTrue') . '>' . csrf_field() . '<input type="hidden" name="id" value="' . $leads->id . '"><input type="submit" class="btn btn_defect disabledSubmitLead" value="x"></form>';
                    } else {
                        return '<button class="btn btn_defect" disabled>x</button>';
                    }
                }
            })
            ->escapeColumns([])
            ->make(true);

    }

    public static function searchContactByPhone($number)
    {
        return Contact::query()
            ->join('contact_phones', 'contact_phones.contact_id', '=', 'contacts.id')
            ->where('contact_phones.phone', 'like', $number . '%')
            ->get()
            ->toArray();
    }

    public static function searchLeadByPhone($number)
    {
        return self::query()
            ->where('leed_phone', 'like', '%' . $number . '%')
            ->with('region')
            ->with('status')
            ->with('manager');
    }

    public static function countLeadsByUserId(array $user_ids, $period = [], $lead_status = null, $isRejected = 0)
    {
        if (!$lead_status) {
            return self::query()
                ->whereBetween('created_at', [$period['leadDateFrom'], $period['leadDateTo']])
                ->where('rejected_lead', '=', $isRejected)
                ->whereIn('user_id', $user_ids)
                ->count('id');
        }
        if($isRejected == 0){
            return self::query()
                ->whereBetween('created_at', [$period['leadDateFrom'], $period['leadDateTo']])
                ->where('status_id', $lead_status)
                ->whereIn('user_id', $user_ids)
                ->count('id');
        }else{
            return self::query()
                ->whereBetween('created_at', [$period['leadDateFrom'], $period['leadDateTo']])
                ->where('rejected_lead', $isRejected)
                ->whereIn('user_id', $user_ids)
                ->count('id');
        }
    }

    public static function getOneClientLead($var)
    {
        return self::query()
            ->where('rejected_lead', 0)
            ->where('contact_id', $var)
            ->with('region')
            ->with('contact')
            ->with('status')
            ->get();
    }

    public static function getOneClientLeadCount($var)
    {
        return self::query()
            ->where('rejected_lead', 0)
            ->where('contact_id', $var)
            ->count();
    }

    public static function checkAccountPay($request)
    {
        return $request;
    }

    public static function getLeadStatByStatuses($filters, $group_id)
    {
        /*
            $group_id не применяется, так как нет возможности массово определить канал лида без лишней нагрузки
        */
        return self::select(
                DB::Raw(
                    'SUM(CASE WHEN `status_id`=5 AND `rejected_lead`=0 THEN 1 ELSE 0 END) as `status_5`,
                    SUM(CASE WHEN `status_id`=11 AND `rejected_lead`=0 THEN 1 ELSE 0 END) as `status_11`,
                    SUM(CASE WHEN `status_id`=12 AND `rejected_lead`=0 THEN 1 ELSE 0 END) as `status_12`,
                    SUM(CASE WHEN `status_id`=13 AND `rejected_lead`=0 THEN 1 ELSE 0 END) as `status_13`,
                    SUM(CASE WHEN `status_id`=14 AND `rejected_lead`=0 THEN 1 ELSE 0 END) as `status_14`,
                    SUM(CASE WHEN `status_id`=15 AND `rejected_lead`=0 THEN 1 ELSE 0 END) as `status_15`,
                    SUM(CASE WHEN `rejected_lead`=1 THEN 1 ELSE 0 END) as `rejected_count`'
                )
            )
            ->whereRaw("(`contact_id`,`id`) IN (SELECT `contact_id`, MAX(id) FROM leeds GROUP BY `contact_id`)")
            ->whereBetween('created_at', [$filters['leadDateFrom'], $filters['leadDateTo']])
            ->get();
    }

    public static function addPromoLead(LeedController $request)
    {

    }


}
