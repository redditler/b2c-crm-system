<?php
/**
 * Created by PhpStorm.
 * User: pomazan_rn
 * Date: 04.11.2019
 * Time: 8:46
 */

namespace App\Support\UserRole;


use App\Regions;
use App\User;
use App\UserBranches;
use App\UserGroups;
use App\UserRegions;

class UserRoleAdmin implements UserRole
{

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * return array
     */
    public function getUserChildren()
    {
        return User::getWorkUser()
            ->where(function ($q){
                foreach (UserGroups::getUserGroup() as $group){
                    $q->orWhere('group_id', $group->id);
                }
            })
            ->get();
    }


    public function getUserRegion()
    {
        return Regions::query()->where(function ($q){
            foreach (UserRegions::query()->where('user_id', $this->id)->get() as $user){
                $q->orWhere('id', $user->region_id);
            }
        })->get();
    }

    /**
     * return array
     */
    public function getUserSalon()
    {
        return UserBranches::getActiveBranches()
            ->where(function ($q){
            foreach (UserGroups::getUserGroup() as $group){
                $q->orWhere('group_id', $group->id);
            }
        })->get();
    }
}