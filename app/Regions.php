<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Self_;

class Regions extends Model
{

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_regions', 'region_id', 'user_id');
    }


    public function getRegion(int $id)
    {
        return Model::find($id);
    }

    public static function getRegions()
    {
        $regions = [];
        foreach (self::all() as $region){
            $regions[$region->id] = $region;
        }
        return $regions;
    }


    public static function getUserRegions($groupId = [])
    {
        $regions= [];
        $regionAll = self::getRegions();
        if(!empty($groupId) && $groupId[0] == 1){
            $userRegions = UserRegions::where('region_id', '=', 13)->get();
        }else if(Auth::user()->group_id == 3) {
            $userRegions = UserRegions::where('user_id', Auth::user()->id)->get();
        } else {
            $userRegions = UserRegions::where('user_id', Auth::user()->id)
                ->where('region_id', '!=', 13)->get();
        }

        if (Auth::user()->role_id == 4){
            return UserRm::getRegions();
        }

        foreach ($userRegions as $region){
            $regions[$region->region_id] = ['id' =>$region->region_id, 'name' => $regionAll[$region->region_id]->name];
        }

       return $regions;
    }

    public static function checkChangeApi($var)
    {
        DB::transaction(function () use ($var) {
            DB::table('regions')
                ->where('id', $var->id)
                ->update(['api' => $var->checked == 'true' ? 1 : 0]);
        });
        return self::where('id', $var->id)->first()->api;
    }

    public static function getApiRegion()
    {
        return self::query()
            ->where('api', 1);
    }

}
