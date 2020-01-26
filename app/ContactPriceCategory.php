<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactPriceCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'description'
    ];

    public function contact()
    {
        return $this->hasMany('App\ContactNew', 'price_category_id', 'id');
    }
}
