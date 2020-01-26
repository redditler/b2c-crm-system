<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactHistory extends Model
{
    protected $fillable = [
        'client_id', 'user_id', 'description'
    ];

    protected $table = 'contact_history';

    public static function clientHistory($id)
    {
        return self::query()->where('client_id', $id);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo('App\ContactNew', 'client_id', 'id');
    }

}
