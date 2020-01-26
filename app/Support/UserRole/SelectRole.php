<?php
/**
 * Created by PhpStorm.
 * User: pomazan_rn
 * Date: 04.11.2019
 * Time: 10:28
 */

namespace App\Support\UserRole;


use App\User;
use Illuminate\Support\Facades\Auth;

class SelectRole
{
    public static function selectRole($var)
    {
        $user = $var ?? Auth::user();
        if ($user->role_id == 1){
            return new UserRoleAdmin($user->id);
        }elseif ($user->role_id == 2){
            return new UserRoleChief($user->id);
        }elseif ($user->role_id == 3){
            return new UserRoleManager($user->id);
        }elseif ($user->role_id == 4){
            return new UserRoleRegionManager($user->id);
        }elseif ($user->role_id == 5){
            return new UserRoleCallManager($user->id);
        }
    }

    public static function getRegionManager($array)
    {

        /**
         * only one group
         */
        return User::getWorkUser()
            ->where('group_id', $array)
            ->where('role_id', 4)
            ->get();
    }

    /**
     * @param $array
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     * one or many salons
     */

    public static function getManagerSalon($array)
    {
        return User::getWorkUser()
            ->where('role_id', 3)
            ->where(function ($q) use($array){
                if (!empty($array)){
                    foreach ($array as $item){
                    $q->orWhere('branch_id', $item);
                    }
                }
            })
            ->get();
    }
}