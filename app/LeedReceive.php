<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeedReceive extends Model
{
    protected $fillable = [
        'title', 'slug'
    ];
    protected $table = 'leed_receives';

}
