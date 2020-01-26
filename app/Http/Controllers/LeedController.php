<?php

namespace App\Http\Controllers;

use App\ContactHistory;
use App\ContactNew;
use App\LeedStatus;
use App\User;
use App\UserBranches;
use App\UserRegions;
use App\UserRm;
use Event;
use App\Events\AddLeed;
use App\Events\AddLeedPromo;

use App\LeedIps;
use App\Regions;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Leed;
use App\Promo;
use App\Contact;
use App\ContactPhones;
use Carbon\Carbon;
use function MongoDB\BSON\fromJSON;

class LeedController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLeeds(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leed_name' => 'required|regex:/^[a-zа-яA-ZА-ЯёїіҐґЇІЁЄє\-\.\' ]{1,30}$/u',
            'leed_phone' => 'required|digits:10',
            'leed_region_id' => 'required|numeric',
            'label_id' => 'sometimes|numeric',
            'leed_city' => 'sometimes|string',
            //'client_ip' => 'required|ip'
        ]);
        if ($validator->fails()) {
            $forLog = "\r\n " . date("Y-m-d H:i:s") . " - validation error" . "\r\n " . json_encode($validator->errors()->all()) . "\r\n " . json_encode($request->toArray());
            $f = fopen("log.txt", "a+");
            fwrite($f, $forLog);
            fclose($f);
            return response()->json(['message' => $validator->errors()->all()], 400);
        }


        $contact = ContactNew::query()->where('id',ContactPhones::getIdWithPhone($request->leed_phone))->first();
        $contactPhone = $contact ? ContactPhones::getPrimaryPhone($contact->id) : null;

        $client_ip = $request->ip();
        $ip = $request->ip();
        unset($request['client_ip']);
        $leed = new Leed();

        if ($contact){

            ContactHistory::create([
                'client_id' => $contact->id,
                'user_id' => 0,
                'description' => 'Повторное обращение клиента ID: '.$contact->id.' !'
            ]);
            $leed->leed_name = htmlspecialchars($contact->fio);
            $leed->leed_phone = $contactPhone->phone;
            $leed->contact_id = $contact->id;
            $leed->leed_region_id = $contact->region_id ? $contact->region_id :$request->leed_region_id;
            $leed->label_id = 1;

        }else{
            $newContact =  ContactNew::create([
                'fio' => $request->leed_name,
                'region_id' => $request->leed_region_id,
                'user_id' => 0,
                'group_id' => $request->leed_region_id == 13 ? 1 : 2,
            ]);

            $leed->leed_name = $request->leed_name;
            $leed->leed_phone = $request->leed_phone;
            $leed->contact_id = $newContact->id;
            $leed->leed_region_id = $request->leed_region_id;
            $leed->label_id = 1;



            ContactHistory::create([
                'client_id' => $newContact->id,
                'user_id' => 0,
                'description' => 'Контакт создан на сайте компании, ID: '.$newContact->id.' / '. $request->leed_name,
            ]);

            ContactPhones::create([
                'contact_id' => $newContact->id,
                'phone' => $request->leed_phone,
                'primary' => 1
            ]);
        }

        if ($leed->save()) {
            $leed_ips = new LeedIps;
            $leed_ips->leed_id = $leed->id;
            $leed_ips->ip = $ip;
            $leed_ips->client_ip = $client_ip;
            $leed_ips->save();

            // Sending notification
           // Event::fire(new AddLeed($request));

            return response()->json(['message' => 'success'], 200);
        } else {
            return response()->json(['message' => 'db error'], 500);
        }
    }

    public function addLeedsPromo(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'client_name' => 'required|regex:/^[a-zа-яA-ZА-ЯёїіҐґЇІЁЄє\-\.\' ]{1,30}$/u',
            'client_phone' => 'required|digits:10',
            'branch_id' => 'required|numeric',
            'promo' => 'required|string',
        ]);
        if ($validator->fails()) {
            $forLog = "\r\n " . date("Y-m-d H:i:s") . " - validation error" . "\r\n " . json_encode($validator->errors()->all()) . "\r\n " . json_encode($request->toArray());
            $f = fopen("log.txt", "a+");
            fwrite($f, $forLog);
            fclose($f);
            return response()->json(['message' => $validator->errors()->all()], 400);
        }


        $contact = ContactNew::query()->where('id',ContactPhones::getIdWithPhone($request->client_phone))->first();
        $contactPhone = $contact ? ContactPhones::getPrimaryPhone($contact->id) : null;

        $userBranches = UserBranches::find($request->branch_id);

        $leadSetting = new \stdClass();
        $leadSetting->branch_id = $request->branch_id;
        $leadSetting->region_id = $userBranches->region_id;
        $leadSetting->group_id = $userBranches->group_id;
        $leadSetting->user_id = User::getWorkUser()->where('branch_id', $request->branch_id)->first()->id ??  User::where('branch_id', $request->branch_id)->first()->id;

        //return  response()->json($leadSetting);

        $client_ip = $request->ip();
        $ip = $request->ip();
        unset($request['client_ip']);
        $leed = new Leed();

        if ($contact){

            ContactHistory::create([
                'client_id' => $contact->id,
                'user_id' => $contact->user_id ?? $leadSetting->user_id,
                'description' => 'Повторное обращение клиента ID: '.$contact->id.' !'
            ]);
            $leed->leed_name = htmlspecialchars($contact->fio);
            $leed->leed_phone = $contactPhone->phone;
            $leed->contact_id = $contact->id;
            $leed->leed_region_id = $contact->region_id ?? $leadSetting->region_id;
            $leed->label_id = 1;
            $leed->user_id =  $contact->user_id ?? $leadSetting->user_id;
            $leed->leed_type_id = 2;
            $leed->leed_receive_id = 4;

        }else{
            $newContact =  ContactNew::create([
                'fio' => $request->client_name,
                'region_id' => $leadSetting->region_id,
                'user_id' => $leadSetting->user_id,
                'group_id' =>  $leadSetting->group_id,
            ]);

            $leed->leed_name = $request->client_name;
            $leed->leed_phone = $request->client_phone;
            $leed->contact_id = $newContact->id;
            $leed->leed_region_id = $leadSetting->region_id;
            $leed->user_id = $leadSetting->user_id;
            $leed->label_id = 1;
            $leed->leed_type_id = 2;
            $leed->leed_receive_id = 4;



            ContactHistory::create([
                'client_id' => $newContact->id,
                'user_id' => $leadSetting->user_id,
                'description' => 'Контакт создан на сайте компании, ID: '.$newContact->id.' / '. $request->client_name,
            ]);

            ContactPhones::create([
                'contact_id' => $newContact->id,
                'phone' => $request->client_phone,
                'primary' => 1
            ]);
        }

        if ($leed->save()) {
            $leed_ips = new LeedIps;
            $leed_ips->leed_id = $leed->id;
            $leed_ips->ip = $ip;
            $leed_ips->client_ip = $client_ip;
            $leed_ips->save();

            $promoLead = new Promo();
            $promoLead->leed_id = $leed->id;
            $promoLead->promo_code = $request->promo;
            $promoLead->promo_discount = 0;
            $promoLead->promo_phone = $request->client_phone;
            $promoLead->save();



            // Sending notification
//            Event::fire(new AddLeedPromo($request));

            return response()->json(['message' => 'success'], 200);
        } else {
            return response()->json(['message' => 'db error'], 500);
        }

//        $validator = Validator::make($request->all(), [
//            'leed_name' => 'required|regex:/^[a-zа-яA-ZА-ЯёїіҐґЇІЁЄє\-\.\' ]{1,30}$/u',
//            'leed_phone' => 'required|digits:10',
//            'branch_id' => 'required|numeric',
//            'client_ip' => 'required|ip'
//        ]);
//        if ($validator->fails()) {
//            $forLog = "\r\n " . date("Y-m-d H:i:s") . " - validation error" . "\r\n " . json_encode($validator->errors()->all()) . "\r\n " . json_encode($request->toArray());
//            $f = fopen("log.txt", "a+");
//            fwrite($f, $forLog);
//            fclose($f);
//            return response()->json(['message' => 'validation error'], 400);
//        }
//
//        $this->saveContact(['fio' => $request->leed_name, 'phone' => $request->leed_phone, 'email' => $request->leed_email]);
//
//        $client_ip = $request->client_ip;
//        $ip = $request->ip();
//        unset($request['client_ip']);
//        $leed = new Leed();
//        $leed->fill($request->all());
//        $leed->status_id = 5;
//        $leed->leed_type_id = 2;
//        if ($leed->save()) {
//            $leed_ips = new LeedIps;
//            $leed_ips->leed_id = $leed->id;
//            $leed_ips->ip = $ip;
//            $leed_ips->client_ip = $client_ip;
//            $leed_ips->save();
//
//            $promo = new Promo;
//            $promo->leed_id = $leed->id;
//            $promo->promo_code = $request->leed_promo_code;
//            $promo->promo_discount = 0 /*$request->leed_promo_discount <= 33 ? $request->leed_promo_discount : 33*/;
//            $promo->promo_phone = $request->leed_phone;
//            $promo->save();
//
//            // Sending notification
//            Event::fire(new AddLeedPromo($request));
//
//            return response()->json(['message' => 'success'], 200);
//        } else {
//            return response()->json(['message' => 'db error'], 500);
//        }
    }

    /**
     * API
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIp()
    {
        $ip = $_SERVER['REMOTE_ADDR']
            ? $_SERVER['REMOTE_ADDR']
            : intval(0);

        if (isset($_SERVER['HTTP_REFERER'])) {
            $pos = strpos($_SERVER['HTTP_REFERER'], 'steko.');
            if ($pos !== FALSE) {
                return response()->json(['ip' => $ip], 200);
            }
        }
        return response()->json(['message' => 'access denied'], 403);
    }

    public function saveContact($contact_arr)
    {

        $valid_arr = [
            'fio' => 'required|max:255',
            'phone' => 'required|digits:10|unique:contact_phones'
        ];

        if (key_exists('email', $contact_arr)) {
            $valid_arr['email'] = 'required|email';
        }

        $validator = Validator::make($contact_arr, $valid_arr);

        if (!$validator->fails()) {
            $contact = new Contact;
            $contact->fio = $contact_arr['fio'];
            if (key_exists('email', $contact_arr)) {
                $contact->email = $contact_arr['email'];
            }
            $contact->save();

            $now = Carbon::now('utc')->toDateTimeString();
            $phone = [
                'contact_id' => $contact->id,
                'phone' => $contact_arr['phone'],
                'created_at' => $now,
                'updated_at' => $now
            ];
            ContactPhones::insert($phone);
        }
    }

    public function getRegions(Request $request)
    {
        $regions = Regions::getApiRegion()
            ->orderBy('region_order', 'ASC')
            ->get(array('id', 'name', 'region_order as order'));

        return response()->json($regions, 200);
    }
    public function getBranches(Request $request)
    {
        $branches = UserBranches::query()
            ->select('id', 'group_id', 'name')
            ->get();

        return response()->json($branches, 200);
    }

//    public function leedsAll()
//    {
//        $user = Auth::user();
//        $regions = Regions::getRegions();
//        $leedStatuses = LeedStatus::getLeedStauses();
//
//        if ($user->role_id == 4) {
//            $userRegions = UserRm::getRegions();
//        } else {
//            $userRegions = UserRegions::where('user_id', $user['id'])->get()->toArray();
//        }
//
//        $leeds = Leed::where('status_id', '!=', 10)
//            ->where(function ($q) use ($userRegions, $user) {
//                foreach ($userRegions as $region) {
//                    if ($user->role_id == 4) {
//                        $q->orWhere('leed_region_id', $region['id']);
//                    } else {
//                        $q->orWhere('leed_region_id', $region['region_id']);
//                    }
//                }
//            })->where('created_at','>=', Carbon::now()->subMonth(3)->format('Y-m-d H:m:s'))
//            ->get()->toArray();
//
//
//        return view('dataTable', [
//            'leeds' => json_encode($leeds),
//            'regions' => json_encode($regions),
//            'leedStatuses' => json_encode($leedStatuses)
//
//        ]);
//    }
//
//    public function leedsUpdate(Request $request, $id)
//    {
//            dd($id, $request->all());
//        return '123';
//    }

}
