<?php
/**
 * Created by PhpStorm.
 * User: pomazan_rn
 * Date: 24.04.2019
 * Time: 11:16
 */

namespace App\Support;


class Colors
{
    public static function colorLeadStatus()
    {
        return [
            'color_5' => 'background: #01D2F0;',
            'color_25' => 'background: #00AFC8;',
            'color_50' => 'background: #39485F;',
            'color_75' => 'background: #1F2C4E;',
            'color_100' => 'background: #FD6F75;',
            'color_125' => 'background: #FF9099;',
        ];
    }

    public static function colorProgressBar($color, $percent)
    {
        return '<div class="progress-bar" style="width: ' . $percent . '%;' . $color . '"><span class="sr-only">' . $percent . '% Complete (success)</span></div>';
    }
}