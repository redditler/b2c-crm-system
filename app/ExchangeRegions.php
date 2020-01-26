<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExchangeRegions extends Model
{
    protected $connection = 'mysql_exchange';
    protected $table = 'region_reg';
}
