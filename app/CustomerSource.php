<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSource extends Model
{
    protected $fillable = [
        'id', 'contact_id', 'name', 'alias', 'description'
    ];

    public function contactNew()
    {
        return $this->belongsTo('App\ContactNew', 'id', 'sources_id');
    }
}
