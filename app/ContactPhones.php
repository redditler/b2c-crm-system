<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContactPhones extends Model
{
    protected $fillable = [
        'id', 'contact_id', 'phone', 'primary'
    ];

    public function contactNew()
    {
        return $this->belongsTo('App\ContactNew', 'contact_id', 'id');
    }

    public static function phoneNumber($id)
    {
        return !empty($id) ? self::where('id', $id)->first()->phone : null;
    }

    public static function getIdWithPhone($phone)
    {
        return self::query()->where('phone', substr($phone, -10))->first() ? self::query()->where('phone', substr($phone, -10))->first()->contact_id : null;
    }

    public static function getIdLikePhone($phone)
    {
        return self::query()->where('phone', 'like',$phone.'%')->get();
    }

    public static function getPrimaryPhone($id)
    {
        return self::query()
            ->where('contact_id', $id)
            ->where('primary', 1)
            ->first() ? self::query()
                ->where('contact_id', $id)
                ->where('primary', 1)
                ->first() :
            self::query()
                ->where('contact_id', $id)
                ->first();
    }

    public static function addContactPhone($contactPhoneArray)
    {
        if(ContactPhones::getIdWithPhone($contactPhoneArray['phone'])){
            return false;
        }

        return DB::table('contact_phones')->insertGetId(
            [
                'phone' => $contactPhoneArray['phone'],
                'contact_id' => $contactPhoneArray['contact_id'],
                'primary' => $contactPhoneArray['primary']
            ]
        );
    }

    public static function removeContactPhone($phoneId)
    {
        return DB::table('contact_phones')->where('id', $phoneId)->delete();
    }

    public function messangers()
    {
        return $this->hasMany('App\Messangers', 'phone_id', 'id');
    }
}
