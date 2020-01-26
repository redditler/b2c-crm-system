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
use App\UserRm;

class UserRoleRegionManager implements UserRole
{

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }


    public function getUserChildren()
    {
        return User::getWorkUser()
            ->where('id', '!=', $this->id)
            ->where(function ($q) {
                foreach ($this->getUserSalon() as $value) {
                    $q->orWhere('branch_id', $value->id);
                }
            })
            ->get();
    }

    /**
     * return user regions
     */
    public function getUserRegion()
    {
        return Regions::query()->where(function ($q) {
            foreach ($this->getUserSalon() as $salon) {

                $q->orWhere('id', $salon->region_id);
            }
        })->get();
    }

    /**
     * return user branches
     */
    public function getUserSalon()
    {
        $salonId = UserRm::query()->where('user_id', $this->id)->get();

        return UserBranches::query()
            ->where(function ($q) use ($salonId) {
            foreach ($salonId as $salon) {
                $q->orWhere('id', $salon->user_branch_id);
            }
        })->get();
    }
}