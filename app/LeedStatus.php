<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeedStatus extends Model
{
    public static $leadStatuses = [5,11,12,13,14,15];

    public static function getLeedStauses()
    {
        $leedStatuses = [];
        foreach (self::all() as $leed){
            $leedStatuses[$leed->id] = $leed;
        }
        return $leedStatuses;
    }
}
