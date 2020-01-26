<?php

namespace App\ConnectAsterisk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CallAll extends Model
{

    public static function getPhoneTable($periodBegin=false, $periodEnd=false)
    {
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,  true);
        curl_setopt($handler, CURLOPT_USERAGENT,       'steko_callog_crm_connecteur/0.1a');
        curl_setopt($handler, CURLOPT_URL,             'https://stat.steko.com.ua/api/list');
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST,   'POST');
        curl_setopt($handler, CURLOPT_POSTFIELDS,      [
            'filter_date_begin' => ($periodBegin>0 ? $periodBegin : time()-(86400*7)),
            'filter_date_end'   => ($periodEnd>0 ? $periodEnd : time()+30),
            'token'             => Auth::user()->callog_api_key
        ]);
        curl_setopt($handler, CURLOPT_HEADER,          false);
        curl_setopt($handler, CURLOPT_SSL_VERIFYPEER,  0);
        curl_setopt($handler, CURLOPT_SSL_VERIFYHOST,  0);
        $out = curl_exec($handler);
        $status = curl_getinfo($handler, CURLINFO_HTTP_CODE);
        curl_close($handler);
        return response()->json($out);
    }


}
