<?php

namespace App\Http\Controllers;

use App\ConnectAsterisk\CallAll;
use App\Contact;
use App\ContactNew;
use App\ContactPhones;
use App\Regions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CallAllController extends Controller
{
    public function index()
    {
        if (Auth::user()->role_id == 3 || Auth::user()->role_id == 4){
            return redirect('/');
        }
        if(Auth::user()->callog_api_key !== null){
            return view('asterisk.index');
        }else{
            return view('asterisk.unavailable');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getPhoneTable(Request $request)
    {

        $dateTime['dateIn'] = !empty($request['dateIn']) ? Carbon::make($request['dateIn'].' 00:00:00')->getTimestamp() : Carbon::now()->subDays(14)->getTimestamp();
        $dateTime['dateFrom'] = !empty($request['dateFrom']) ? Carbon::make($request['dateFrom'].' 23:59:59')->getTimestamp() : Carbon::now()->getTimestamp();

        $call = json_decode(CallAll::getPhoneTable($dateTime['dateIn'], $dateTime['dateFrom'])->getData(0), 1);

        foreach($call['callog'] as &$thisCallEvent){
            if(($thisCallEvent['direction'] == 1) or (($thisCallEvent['direction'] == 2) and (strlen($thisCallEvent['caller']) == 13))){
                $thisCallEvent['operator'] = $thisCallEvent['callee'];
                $thisCallEvent['client'] = $thisCallEvent['caller'];
                if(($thisCallEvent['direction'] == 2) and (strlen($thisCallEvent['caller']) == 13)){
                    $thisCallEvent['direction'] = 3;
                    if(isset($thisCallEvent['callee_explained'])){
                        $thisCallEvent['operator_explained'] = [
                            'num'   => $thisCallEvent['callee_explained']['num'],
                            'name'  => $thisCallEvent['callee_explained']['name']
                        ];
                    }
                }else{
                    if(isset($thisCallEvent['caller_explained'])){
                        $thisCallEvent['operator_explained'] = [
                            'num'   => $thisCallEvent['callee_explained']['num'],
                            'name'  => $thisCallEvent['callee_explained']['name']
                        ];
                    }
                }
            }elseif($thisCallEvent['direction'] == 2){
                $thisCallEvent['operator'] = $thisCallEvent['caller'];
                $thisCallEvent['client'] = $thisCallEvent['callee'];
                if(isset($thisCallEvent['callee_explained'])){
                    $thisCallEvent['operator_explained'] = [
                        'num'   => $thisCallEvent['caller_explained']['num'],
                        'name'  => $thisCallEvent['caller_explained']['name']
                    ];
                }
            }
        }

        return DataTables::of($call['callog'])
            ->addColumn('sortDate', function ($call){
                return $call['begin'];
            })
            ->addColumn('eventDate', function ($call){
                return date('d.m.Y', $call['begin']);
            })
            ->addColumn('eventTime', function ($call) {
                return date('H:i:s', $call['begin']);
            })
            ->addColumn('direction', function ($call){
                return $call['direction'];
            })
            ->addColumn('answered', function ($call){
                if(count($call['operator_explained'])>0){
                    return [
                        'num'   => $call['operator_explained']['num'],
                        'name'  => $call['operator_explained']['name']
                    ];
                }else{
                    return $call['operator'];
                }
            })
            ->addColumn('status', function ($call){
                return $call['status'];
            })
            ->addColumn('client', function ($call){
                if((Auth::user()->callog_api_key == null) or (Auth::user()->callog_num_list == null)){
                    return $call['client'];
                }else{
                    return '<span class="btn btn-sm btn-success call-request" data-client-num="'.$call['client'].'" style="float:left;"><span class="glyphicon glyphicon glyphicon-earphone" aria-hidden="true"></span></span> '.$call['client'];
                }
            })
            ->addColumn('clientStatus', function ($call){
                return ContactPhones::getIdWithPhone(substr($call['client'], -10)) ?
                    '<a href="/contact/'.ContactPhones::getIdWithPhone(substr($call['client'], -10)).'/edit" class="btn btn-info">'.
                        ContactNew::where('id', ContactPhones::getIdWithPhone(substr($call['client'], -10)) )->first()->fio.'</a>' :
                    'Новый клиент';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function requestWebcall(Request $request)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,  true);
        curl_setopt($curl, CURLOPT_USERAGENT,       'steko_callog_crm_connecteur/0.1a');
        curl_setopt($curl, CURLOPT_URL,             'https://stat.steko.com.ua/api/webcall');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST,   'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS,      [
            'source'        => $request->source,
            'destination'   => $request->destination,
            'token'         => Auth::user()->callog_api_key
        ]);
        curl_setopt($curl, CURLOPT_HEADER,          false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,  0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,  0);
        $out = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return response()->json($out);
    }

    public function getCallerID(Request $request)
    {
        if(!isset($_SERVER['REMOTE_ADDR'])): redirect('login'); endif;
        if($_SERVER['REMOTE_ADDR'] != "172.17.3.35"): redirect('login'); endif;
        if($request->api_key == "7fd9e4f9-0a33-4f50-a7cf-b1244a53847b"){
            $contactID = ContactPhones::getIdWithPhone($request->callerid);
            if($contactID>0){
                $getContactDetails = Contact::where('id', $contactID)->get();
                $resolveRegion = Regions::where('id', $getContactDetails->first()->region_id)->get();
                $return = [
                    'exists'    => true,
                    'name'      => $getContactDetails->first()->fio,
                    'channel'   => $getContactDetails->first()->group_id,
                    'region'    => $resolveRegion->first()->name,
                    'region_id' => $getContactDetails->first()->region_id,
                    'contact'   => $contactID
                ];
            }else{
                $return['exists'] = false;
            }
            return response()->json($return);
        }else{
            return redirect('login');
        }
    }

}
