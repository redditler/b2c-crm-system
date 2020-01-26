<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = [
        'id', 'region_id', 'field_1', 'field_2',
        'field_3', 'field_4', 'field_5', 'field_6',
        'field_7', 'field_8', 'field_9'
    ];

    public function props()
    {
        return $this->hasMany('App\Prop', 'field_id');
    }
}
