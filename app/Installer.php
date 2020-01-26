<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Installer extends Model
{
    protected $fillable = [
        'name', 'surname', 'phone', 'email', 'district_id', 'select_2', 'text', 'ip'
    ];
}
