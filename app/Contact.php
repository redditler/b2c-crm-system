<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CustomerSource;
use DB;

class Contact extends Model
{
    protected $fillable = [
        'fio', 'region_id', 'city', 'email', 'user_id', 'group_id', 'diler', 'comment', 'created_at'
    ];

    public function group()
    {
        return $this->hasOne('App\UserGroups', 'id', 'group_id');
    }

    public function regions()
    {
        return $this->hasOne('App\Regions', 'id', 'region_id');
    }

    public function phones()
    {
        return $this->hasMany('App\ContactPhones', 'contact_id', 'id');
    }

    public function manager()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public static function getQualifyCount($period=false){
        $return = self::select(DB::raw('COUNT(0) as `count`, `contact_quality_id`'));
        if(isset($period['userGroup'])){
            $return = $return->where('group_id', $period['userGroup']);
        }
        if((isset($period['leadDateFrom'])) and (isset($period['leadDateTo']))){
            $return = $return->whereBetween('created_at', [$period['leadDateFrom'], $period['leadDateTo']]);
        }
        $return = $return->groupBy('contact_quality_id')->get();
        return $return;
    }

    public static function getContactsSources($period=false){
        $getSources = CustomerSource::get();
        foreach($getSources as $thisSource){
            $resolveSource[$thisSource->id] = $thisSource->name;
        }
        $return = self::select(DB::raw('COUNT(0) as `count`, `sources_id`'));
        if(isset($period['userGroup'])){
            $return = $return->where('group_id', $period['userGroup']);
        }
        if((isset($period['leadDateFrom'])) and (isset($period['leadDateTo']))){
            $return = $return->whereBetween('created_at', [$period['leadDateFrom'], $period['leadDateTo']]);
        }
        $return = $return->groupBy('sources_id')->get();
        if($return->count()>0){
            foreach($return as &$thisRow){
                $thisRow->sources_id = (isset($resolveSource[$thisRow->sources_id]) ? $resolveSource[$thisRow->sources_id] : $thisRow->sources_id);
            }
        }
        return $return;
    }

    public static function getContactsGenders($period=false){
        $return = self::select(DB::raw('COUNT(0) as `count`, `gender`'));
        if(isset($period['userGroup'])){
            $return = $return->where('group_id', $period['userGroup']);
        }
        if((isset($period['leadDateFrom'])) and (isset($period['leadDateTo']))){
            $return = $return->whereBetween('created_at', [$period['leadDateFrom'], $period['leadDateTo']]);
        }
        $return = $return->groupBy('gender')->get();
        return $return;
    }

    public static function getContactsAges($period=false){
        $return = self::select(DB::raw('COUNT(0) as `count`, `age`'));
        if(isset($period['userGroup'])){
            $return = $return->where('group_id', $period['userGroup']);
        }
        if((isset($period['leadDateFrom'])) and (isset($period['leadDateTo']))){
            $return = $return->whereBetween('created_at', [$period['leadDateFrom'], $period['leadDateTo']]);
        }
        $return = $return->groupBy('age')->get();
        return $return;
    }

}
