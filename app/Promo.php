<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'leed_id', 'promo_code', 'promo_discount', 'promo_phone', 'promo_email'
    ];

    public function leed()
    {
        return $this->belongsTo('App\Leed', 'leed_id', 'id');
    }

    public static function getPromoLead()
    {
        return self::query();
    }

    public static function getPromoKey()
    {
        $promos = [];
        foreach (self::all()->toArray() as $promo){
            $promos[$promo['leed_id']] = $promo;
        }
        return $promos;
    }
}
