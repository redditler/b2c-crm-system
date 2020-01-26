<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExchangeDistrics extends Model
{
    protected $connection = 'mysql_exchange';
    protected $table = 'region_distr';

    public function area()
    {
        return $this->hasOne('App\ExchangeAreas', 'id', 'area_id');
    }

    public function region()
    {
        return $this->hasOne('App\ExchangeRegions', 'id', 'reg_id');
    }

}
