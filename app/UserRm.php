<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserRm extends Model
{
    protected $fillable = [
        'user_id', 'user_branch_id'
    ];
    protected $table = 'user_rms';

    public static function rmBranches()
    {
        $rmSalons = self::query()
            ->where('user_id',  Auth::user()->id)
            ->get();

       return UserBranches::query()
        ->where(function ($q) use ($rmSalons) {
            foreach ($rmSalons as $salon) {
                $q->orWhere('id', $salon->user_branch_id);
            }
        })->get();
    }




    public static function getRegions()
    {
        $regionAll = Regions::all();
        $regions = [];
        foreach ($regionAll as $region){
            $regions[$region->id] = $region;
        }

        $regionRm = [];
        foreach (self::rmBranches() as $branch){
             $regionRm[$branch->region_id] = $regions[$branch->region_id];
        }

        return $regionRm;
    }


    public static function rmLeeds()
    {
        return Leed::query()
            ->where(function ($q){
            foreach (self::rmBranches() as $branch ){
            $q->orWhere('leed_region_id', $branch->region_id);
            }
        })->get();
    }

    public static function rmReports()
    {
        return DailyReport::query()
            ->where(function ($q){
            foreach (self::rmBranches() as $branch){
                $q->orWhere('branch_id', $branch->id);
            }
        })->get();
    }

    public static function rmPlanBranches()
    {
        return MonthlyPlan::query()
        ->where(function ($q){
            foreach (self::rmBranches() as $branch){
                $q->orWhere('branch_id', $branch->id);
            }
        })
            ->get();
    }

    public static function getRmManagers()
    {
        return User::getWorkUser()
            ->where(function ($q){
           foreach (self::rmBranches() as $branch){
           $q->orWhere('branch_id', $branch->id);
           }
        })
            ->where('role_id', 3);
    }

    public static function getSalonsRegionManagers($request)
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


}
