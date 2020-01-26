<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeedType extends Model
{

    protected $fillable = [
        'title', 'slug'
    ];
    protected $table = 'leed_types';

}
