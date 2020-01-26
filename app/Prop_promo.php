<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prop_promo extends Model
{
    protected $fillable = [
        'id', 'field_id_promo', 'datein',
        'type_code', 'status', 'manager',
        'location', 'datedue', 'comment', 'label',
    ];
}
