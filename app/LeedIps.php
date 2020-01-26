<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeedIps extends Model
{
    protected $fillable = [
        'id', 'leed_id', 'ip', 'client_ip', 'created_at'
    ];
}
