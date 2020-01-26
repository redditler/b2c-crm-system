<?php

namespace App\Http\Controllers;

use App\ContactComment;
use App\Leed;
use App\LeedLabel;
use App\LeedStatus;
use App\Regions;
use App\Support\Colors;
use App\Support\LeadFilter\LeadFilterRender;
use App\User;
use App\UserRegions;
use App\UserRm;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaedsConrroler extends Controller
{

    public function storeLead()
    {
        $regions = Regions::query()
            ->where('status', 1)
            ->orderBy('region_order', 'ASC')
            ->get();
        $labels = LeedLabel::all();

        return view('leads.storeLead', ['regions' => $regions, 'labels' => $labels]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * Создаем новый лид
     */
    public function createLead(Request $request)
    {
       return Leed::insertNewLead($request);

    }

    public function index()
    {
        return view('leads.leads');
    }

    public function leadInWorkShow()
    {
        return view('leads.leadInWork');
    }

    public function leadFrozeShow()
    {
        return view('leads.leadFroze');
    }

    public function leadOfferShow()
    {
        return view('leads.leadOffer');
    }

    public function leadBilledShow()
    {
        return view('leads.leadBilled');
    }

    public function leadPaidShow()
    {
        return view('leads.leadPaid');
    }
    public function leadCanceledShow()
    {
        return view('leads.leadsCanceled');
    }

    public function indexAddEditRemoveColumnData(Request $request)
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

        $leadStatusId = self::processLeadStatusId($request);

        if(!empty($filer['search']['value'])) {
            $leads = Leed::searchLeadByPhone($filer['search']['value']);
        } else {
            $leads = Leed::leadAllFromDataTables($user, $leadPeriodDate, $request, $leadStatusId);
        }

        return Leed::dataTablesLead($leads, $user, $color);
    }

    public static function processLeadStatusId($request)
    {
        if($request->draw == 1) {
            $request->session()->forget('leadStatusId');
        }
        $sessionData = $request->session()->get('leadStatusId');
        if(!empty($request->leadStatusId)) {
            session(['leadStatusId' => $request->leadStatusId]);
            $leadStatusId = $request->leadStatusId;
        } else {
            $leadStatusId = empty($sessionData) ? LeedStatus::$leadStatuses : $sessionData;
        }
        return $leadStatusId;
    }


    public function leadInWork(Request $request)
    {
        $user = Auth::user();

        $filer = $request->all();
        $leadPeriodDate = [];
        $dateFromLead = Leed::dateFromLead();

        $leadPeriodDate['leadDateFrom'] = isset($filer['leadDateFrom']) ? Carbon::make($filer['leadDateFrom'] . ' 00:00:00')->format('Y-m-d H:i:s') : $dateFromLead[0];
        $leadPeriodDate['leadDateTo'] = isset($filer['leadDateTo']) ? Carbon::make($filer['leadDateTo'] . ' 23:59:59')->format('Y-m-d H:i:s') : $dateFromLead[1];

        $color = Colors::colorLeadStatus();

        if ($user->role_id == 4) {
            $userRegions = UserRm::getRegions();
        } else {
            $userRegions = UserRegions::query()
                ->where('user_id', $user->id)
                ->get();
        }


        $leadRegionId = empty($request->leadRegionId) ? Regions::getUserRegions() : $request->leadRegionId;
        if ($user->role_id == 3 || $user->role_id == 5){
            $managers = empty($request->leadsUserId) ? [$user->id => ['id' => $user->id]] : $user->id;
        }else{
            $managers = empty($request->leadsUserId) ? User::userManager() : $request->leadsUserId;
        }

        $leads = Leed::leadAllFromDataTables($user, $leadPeriodDate, $leadRegionId, $request, $managers, [11], $userRegions );

        return Leed::dataTablesLead($leads, $user, $color);
    }

    public function leadFroze(Request $request)
    {
        $user = Auth::user();

        $filer = $request->all();
        $leadPeriodDate = [];
        $dateFromLead = Leed::dateFromLead();

        $leadPeriodDate['leadDateFrom'] = isset($filer['leadDateFrom']) ? Carbon::make($filer['leadDateFrom'] . ' 00:00:00')->format('Y-m-d H:i:s') : $dateFromLead[0];
        $leadPeriodDate['leadDateTo'] = isset($filer['leadDateTo']) ? Carbon::make($filer['leadDateTo'] . ' 23:59:59')->format('Y-m-d H:i:s') : $dateFromLead[1];

        $color = Colors::colorLeadStatus();

        if ($user->role_id == 4) {
            $userRegions = UserRm::getRegions();
        } else {
            $userRegions = UserRegions::query()
                ->where('user_id', $user->id)
                ->get();
        }


        $leadRegionId = empty($request->leadRegionId) ? Regions::getUserRegions() : $request->leadRegionId;
        if ($user->role_id == 3 || $user->role_id == 5){
            $managers = empty($request->leadsUserId) ? [$user->id => ['id' => $user->id]] : $user->id;
        }else{
            $managers = empty($request->leadsUserId) ? User::userManager() : $request->leadsUserId;
        }



        $leads = Leed::leadAllFromDataTables($user, $leadPeriodDate, $leadRegionId, $request, $managers, [12], $userRegions );

        return Leed::dataTablesLead($leads, $user, $color);
    }

        public function leadOffer(Request $request)
    {
        $user = Auth::user();

        $filer = $request->all();
        $leadPeriodDate = [];
        $dateFromLead = Leed::dateFromLead();

        $leadPeriodDate['leadDateFrom'] = isset($filer['leadDateFrom']) ? Carbon::make($filer['leadDateFrom'] . ' 00:00:00')->format('Y-m-d H:i:s') : $dateFromLead[0];
        $leadPeriodDate['leadDateTo'] = isset($filer['leadDateTo']) ? Carbon::make($filer['leadDateTo'] . ' 23:59:59')->format('Y-m-d H:i:s') : $dateFromLead[1];

        $color = Colors::colorLeadStatus();

        if ($user->role_id == 4) {
            $userRegions = UserRm::getRegions();
        } else {
            $userRegions = UserRegions::query()
                ->where('user_id', $user->id)
                ->get();
        }


        $leadRegionId = empty($request->leadRegionId) ? Regions::getUserRegions() : $request->leadRegionId;
        if ($user->role_id == 3 || $user->role_id == 5){
            $managers = empty($request->leadsUserId) ? [$user->id => ['id' => $user->id]] : $user->id;
        }else{
            $managers = empty($request->leadsUserId) ? User::userManager() : $request->leadsUserId;
        }



        $leads = Leed::leadAllFromDataTables($user, $leadPeriodDate, $leadRegionId, $request, $managers, [13], $userRegions );

        return Leed::dataTablesLead($leads, $user, $color);
    }

        public function leadBilled(Request $request)
    {
        $user = Auth::user();

        $filer = $request->all();
        $leadPeriodDate = [];
        $dateFromLead = Leed::dateFromLead();

        $leadPeriodDate['leadDateFrom'] = isset($filer['leadDateFrom']) ? Carbon::make($filer['leadDateFrom'] . ' 00:00:00')->format('Y-m-d H:i:s') : $dateFromLead[0];
        $leadPeriodDate['leadDateTo'] = isset($filer['leadDateTo']) ? Carbon::make($filer['leadDateTo'] . ' 23:59:59')->format('Y-m-d H:i:s') : $dateFromLead[1];

        $color = Colors::colorLeadStatus();

        if ($user->role_id == 4) {
            $userRegions = UserRm::getRegions();
        } else {
            $userRegions = UserRegions::query()
                ->where('user_id', $user->id)
                ->get();
        }


        $leadRegionId = empty($request->leadRegionId) ? Regions::getUserRegions() : $request->leadRegionId;
        if ($user->role_id == 3 || $user->role_id == 5){
            $managers = empty($request->leadsUserId) ? [$user->id => ['id' => $user->id]] : $user->id;
        }else{
            $managers = empty($request->leadsUserId) ? User::userManager() : $request->leadsUserId;
        }



        $leads = Leed::leadAllFromDataTables($user, $leadPeriodDate, $leadRegionId, $request, $managers, [14], $userRegions );

        return Leed::dataTablesLead($leads, $user, $color);
    }

        public function leadPaid(Request $request)
    {
        $user = Auth::user();

        $filer = $request->all();
        $leadPeriodDate = [];
        $dateFromLead = Leed::dateFromLead();

        $leadPeriodDate['leadDateFrom'] = isset($filer['leadDateFrom']) ? Carbon::make($filer['leadDateFrom'] . ' 00:00:00')->format('Y-m-d H:i:s') : $dateFromLead[0];
        $leadPeriodDate['leadDateTo'] = isset($filer['leadDateTo']) ? Carbon::make($filer['leadDateTo'] . ' 23:59:59')->format('Y-m-d H:i:s') : $dateFromLead[1];

        $color = Colors::colorLeadStatus();

        if ($user->role_id == 4) {
            $userRegions = UserRm::getRegions();
        } else {
            $userRegions = UserRegions::query()
                ->where('user_id', $user->id)
                ->get();
        }

        $leadRegionId = empty($request->leadRegionId) ? Regions::getUserRegions() : $request->leadRegionId;
        if ($user->role_id == 3 || $user->role_id == 5){
            $managers = empty($request->leadsUserId) ? [$user->id => ['id' => $user->id]] : $user->id;
        }else{
            $managers = empty($request->leadsUserId) ? User::userManager() : $request->leadsUserId;
        }

        $leads = Leed::leadAllFromDataTables($user, $leadPeriodDate, $leadRegionId, $request, $managers, [15], $userRegions );

        return Leed::dataTablesLead($leads, $user, $color);
    }


    public function leadCanceled(Request $request)
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

        $leadStatusId = self::processLeadStatusId($request);

        if(!empty($filer['search']['value'])) {
            $leads = Leed::searchLeadByPhone($filer['search']['value']);
        } else {
            $leads = Leed::leadAllFromDataTables($user, $leadPeriodDate, $request, $leadStatusId, 1);
        }

        return Leed::dataTablesLead($leads, $user, $color);
    }


    public static function leadFilterAll(Request $request)//@TODO not sort when change group
    {
        $user = Auth::user();
        $filer = $request->all();

        $leadPeriodDate = [];
        $dateFromLead = Leed::dateFromLead();
        $leadPeriodDate['leadDateFrom'] = isset($filer['leadDateFrom']) ? Carbon::make($filer['leadDateFrom'] . ' 00:00:00')->format('Y-m-d H:i:s') : $dateFromLead[0];
        $leadPeriodDate['leadDateTo'] = isset($filer['leadDateTo']) ? Carbon::make($filer['leadDateTo'] . ' 23:59:59')->format('Y-m-d H:i:s') : $dateFromLead[1];



        return Leed::leadAllFromDataTables($user, $leadPeriodDate, $request, false, $rejected_lead = 0)
            ->orderBy('id', 'desc')
            ->get();

    }

    public function updateLead(Request $request)
    {
        $user = Auth::user();

        if($request->ajax()) {
            DB::transaction(function () use ($request, $user) {
                $leed = Leed::where('id', $request->id)->with('contact')->first();

                if ($user->role_id == 5) {
                    $leed->user_id = 0;
                    $leed->cm_id = $user->id;
                    $leed->cm_comment = $request->cm_comment;
                } else {
                    $leed->user_id = $user->id;
                    if ($leed->contact){
                        $leed->contact->user_id = $user->id;
                    }
                }
                $leed->status_id = $request->status_id;
                $leed->comment = $request->comment;
                $leed->push();


                if (!empty($request->comment) && $leed->contact_id){

                $contactComment = new ContactComment();
                $contactComment->contact_id = $leed->contact_id;
                $contactComment->user_id = $user->id;
                $contactComment->comment = $request->comment;
                $contactComment->save();

                }
            });
            return json_encode(["message" => 'Запись успешно обновлена']);
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function startLeadFilter(Request $request)
    {
        return LeadFilterRender::getLeadFilterStep($request);
    }
    public static function secondLeadFilter(Request $request)
    {
        return LeadFilterRender::getValueFilterStepTwo($request);
    }

    public static function renderMultiselect(Request $request)
    {
        $filter = $request->all();

        if(empty($filter['regionsId'])) $filter['regionsId'] = [];

        $rolesId = 3;

        if(Auth::user()->role_id == 5) {
            $rolesId = 5;
        }

        if(!isset($filter['groupId'])) {
            if(Auth::user()->role_id == 4) {
                $filter['groupId'] = [Auth::user()->group_id];
                $filter['regionsId'] = UserRegions::getRegionsIdByUserId();
            }
            else if(Auth::user()->role_id == 5) {
                $filter['groupId'] = [1, 2];
            }
            else{
                $filter['groupId'][0] = Auth::user()->group_id;
            }
        }

        if(!empty($filter['groupId'])) {
            return User::getUserManagerByFilter($filter['groupId'], $rolesId, $filter['regionsId']);
        }
        return [];
    }

    public static function searchCustomerLead(Request $request)
    {
        $exclude = [' ', '+', '-', '(', ')'];
        $searchString = str_replace($exclude, '', $request->get('search'));
        return Leed::searchContactByPhone($searchString);
    }
    public static function getStatusLead(Request $request)
    {
        $contact_id = $request->get('contactId');
        return Leed::where('contact_id', $contact_id)->pluck('status_id')->first();
    }

    public static function oneClientLead(Request $request)
    {
        return Leed::getOneClientLead($request->id);
    }

    public static function createLeadXls(Request $request)
    {
        return view('exportXlsFile.createLeadXls', ['request' => $request]);

    }

    public function getAccountPay(Request $request)
    {
        return Leed::checkAccountPay($request);
    }
}
