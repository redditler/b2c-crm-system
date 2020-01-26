<?php
/**
 * Created by PhpStorm.
 * User: pomazan_rn
 * Date: 04.11.2019
 * Time: 8:49
 */

namespace App\Support\UserRole;


use App\Regions;
use App\User;
use App\UserBranches;
use App\UserRegions;

class UserRoleManager implements UserRole
{

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getUserChildren()
    {
        return User::query()
            ->where('id', $this->id)
            ->get();
    }

    /**
     * return user regions
     */
    public function getUserRegion()
    {
        return Regions::query()->where(function ($q) {
            foreach (UserRegions::query()->where('user_id', $this->id)->get() as $region) {
                $q->orWhere('id', $region->region_id);
            }
        })->get();
    }

    /**
     * return user branches
     */
    public function getUserSalon()
    {
        return UserBranches::query()
            ->where(function ($q) {
                foreach ($this->getUserChildren() as $child) {
                    $q->orWhere('id', $child->branch_id);
                }
            })->get();
    }
}