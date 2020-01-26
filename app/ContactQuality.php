<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactQuality extends Model
{
    protected $fillable = [
        'title', 'description'
    ];

    public function contact()
    {
        return $this->hasMany('App\ContactNew', 'contact_quality_id', 'id');
    }

    public static function getListQuality()
    {
        return self::all();
    }

}
