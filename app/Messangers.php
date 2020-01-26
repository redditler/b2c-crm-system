<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Messangers extends Model
{
    protected $default = [
        'viber',
        'whatsapp',
        'telegram',
        'sms'
    ];

    public static function removeeMessanger($phone_array)
    {
       return self::where('phone_id', $phone_array['id'])->delete();
    }

    public static function addMessanger($phone_array)
    {
        self::removeeMessanger($phone_array);
        DB::table('messangers')->insert($phone_array['messangers']);
    }

    public function contactPhone()
    {
        return $this->belongsTo('App\ContactPhones', 'id', 'phone_id');
    }
}
