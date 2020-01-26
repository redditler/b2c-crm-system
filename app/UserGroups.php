<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserGroups extends Model
{
    public function branch()
    {
        return $this->hasMany('App\UserBranches', 'group_id');
    }

    public static function getGroups()
    {
        return self::query()->get()->toArray();
    }

    public static function getUserGroup()
    {
        if (Auth::user()->group_id != 3){
        return self::query()
            ->where('id', Auth::user()->group_id)
            ->get();

        }elseif (Auth::user()->role_id == 5){
            return self::query()
                ->where('id','!=', 4)
                ->get();
        }
        else{
            return self::query()
                ->get();
        }
    }
}
