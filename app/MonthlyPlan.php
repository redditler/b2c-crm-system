<?php

namespace App;

use Carbon\Carbon;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use PDO;

class MonthlyPlan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'year', 'month', 'branch_id', 'frameworks', 'sum', 'active',
    ];

    public function branch()
    {
        return $this->hasOne('App\UserBranches', 'id', 'branch_id');
    }

    public function dailyReport()
    {
        return $this->hasMany('App\DailyReport', 'branch_id', 'branch_id');

    }
}
