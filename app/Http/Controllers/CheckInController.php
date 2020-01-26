<?php

namespace App\Http\Controllers;

use App\ExchangeDistrics;
use App\Installer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CheckInController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addWorker(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|regex:/^[a-zа-яA-ZА-ЯёїіҐґЇІЁЄє\-\.\' ]{1,30}$/u',
            'surname' => 'required|regex:/^[a-zа-яA-ZА-ЯёїіҐґЇІЁЄє\-\.\' ]{1,30}$/u',
            'phone' => 'required|digits:10|unique:installers',
            'email' => 'required|email|unique:installers',
            'district_id' => 'required|numeric',
            'select_2' => 'required|numeric',
            'text' => 'required|string'
        ]);

        if ($validator->fails()) {
            $forLog = "\r\n " . date("Y-m-d H:i:s") . " - validation error" . "\r\n " . json_encode($validator->errors()->all()) . "\r\n " . json_encode($data);
            $f = fopen("logCheckIn.txt", "a+");
            fwrite($f, $forLog);
            fclose($f);
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
        }
        $data['ip'] = $request->ip();
        $installer = new Installer();
        $installer->fill($data);
        if ($installer->save()) {
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => 'db error'], 500);
        }
    }

    public function getCity(Request $request)
    {
        $search = $request->search;
        if (strlen($search) >= 3) {

            $distr = ExchangeDistrics::where('name', 'like', $search . '%')->get();
            if (count($distr) > 0) {
                $data = array();
                foreach ($distr AS $item) {
                    $data[] = array(
                        'id' => $item->id,
                        'region' => $item->region->name,
                        'area' => $item->area->name,
                        'district' => $item->name
                    );
                }
                return response()->json(['status' => 'success', 'data' => $data], 200);
            }
            return response()->json(['status' => 'error', 'message' => 'not found'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'few characters'], 200);
    }


}
