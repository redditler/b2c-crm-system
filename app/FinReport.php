<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinReport extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'num_order',
        'sum_order',
        'framework_count',
        'discount',
        'name',
        'phone',
        'email',
        'city',
        'street',
        'house',
        'flat',
        'installer',
        'area',
        'date',
        'sum'
    ];

    public function manager()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function branch()
    {
        return $this->hasOne('App\UserBranches', 'id', 'branch_id');
    }

}
