<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class Google2FA extends Model
{
    protected $fillable = [
        'user_id', 'google2fa_enable'
    ];

    protected $table = 'password_securities';

    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'user_id');
    }
}
