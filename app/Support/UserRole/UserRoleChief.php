<?php
/**
 * Created by PhpStorm.
 * User: pomazan_rn
 * Date: 04.11.2019
 * Time: 8:47
 */

namespace App\Support\UserRole;


use App\Regions;
use App\User;
use App\UserBranches;
use App\UserGroups;
use App\UserRegions;

class UserRoleChief implements UserRole
{

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getUserChildren()
    {
        $groupID = User::where('id',$this->id)->first()->group_id;
        return User::getWorkUser()
            ->where('group_id', $groupID)
            ->where('id', '!=', $this->id)
            ->where('role_id', '>', 2)//@TODO
            ->get();
    }

    /**
     * return user regions
     */
    public function getUserRegion()
    {
        return Regions::query()->where(function ($q){
            foreach (UserRegions::query()->where('user_id', $this->id)->get() as $user){
                $q->orWhere('id', $user->region_id);
            }
        })->get();
    }

    /**
     * return user branches
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