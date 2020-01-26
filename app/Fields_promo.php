<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fields_promo extends Model
{
    protected $fillable = [
        'id', 'field_1_promo', 'field_2_promo',
        'region_id', 'field_3_promo', 'field_4_promo',
        'field_5_promo', 'field_6_promo', 'field_7_promo',
        'field_8_promo',  'field_9_promo',
    ];
}
