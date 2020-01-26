<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExchangeAreas extends Model
{
    protected $connection = 'mysql_exchange';
    protected $table = 'region_area';
}
