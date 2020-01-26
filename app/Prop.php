<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prop extends Model
{
    protected $fillable = [
        'id', 'field_id', 'datein',
        'type_code', 'status', 'manager', 'location',
        'datedue', 'comment', 'label'
    ];

    public function fields()
    {
        return $this->belongsTo('App\Field', 'id', 'field_id');
    }
}
