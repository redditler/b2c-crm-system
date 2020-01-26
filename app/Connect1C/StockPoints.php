<?php

namespace App\Connect1C;

use Illuminate\Database\Eloquent\Model;

class StockPoints extends Model
{
    protected $connection = 'mysql_1C';
    protected $table = 'Stock_Points';
}
