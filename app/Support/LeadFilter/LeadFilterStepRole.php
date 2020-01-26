<?php
/**
 * Created by PhpStorm.
 * User: pomazan_rn
 * Date: 12.11.2019
 * Time: 9:33
 */

namespace App\Support\LeadFilter;


use App\Leed;
use App\User;
use App\UserBranches;
use App\UserRm;

class LeadFilterStepRole
{


    public static function analyst($request)
    {
        if ($request->group_id) {
            if ($request->group_id == 1) {
                return self::groupOnline();
            }
            return self::getGroupRm($request);

        } elseif ($request->regionManager_id) {
            return self::getSalonsRegionManagers($request);
        } elseif ($request->salon_id) {
            return self::getUsersSalons($request);
        }

    }

    public static function chief($request)
    {
        if ($request->regionManager_id) {
            return self::getSalonsRegionManagers($request);
        } elseif ($request->salon_id) {
            return self::getUsersSalons($request);
        }

    }

    public static function regionManager($request)
    {
        if ($request->salon_id) {
            return self::getUsersSalons($request);
        }
    }

    public static function callCenterManager($request)
    {
        if ($request->group_id) {
            if ($request->group_id == 1) {
                return self::groupOnline();
            }
            return self::getGroupRm($request);
        } elseif ($request->regionManager_id) {
            return self::getSalonsRegionManagers($request);
        } elseif ($request->salon_id) {
            return self::getUsersSalons($request);

        }

    }

    private static function getGroupRm($request)
    {
        return User::getWorkUser()
            ->where('role_id', 4)
            ->where('group_id', $request->group_id)
            ->get();
    }

    private static function getSalonsRegionManagers($request)
    {
        if (empty($request->regionManager_id)) {
            return '';
        }
        $salonId = UserRm::query()
            ->where(function ($q) use ($request) {
                foreach ($request->regionManager_id as $item) {
                    $q->orWhere('user_id', $item);
                }
            })
            ->get();

        return UserBranches::query()
            ->where(function ($q) use ($salonId) {
                foreach ($salonId as $item) {
                    $q->orWhere('id', $item->user_branch_id);
                }
            })
            ->get();
    }

    private static function getUsersSalons($request)
    {
        return User::getWorkUser()
            ->where('role_id', 3)
            ->where(function ($q) use ($request) {
                foreach ($request->salon_id as $item) {
                    $q->orWhere('branch_id', $item);
                }
            })
            ->get();
    }

    private static function groupOnline()
    {
        return User::getWorkUser()->where('group_id', 1)->where('role_id', 3)->get();
    }

}