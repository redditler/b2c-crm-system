<?php
/**
 * Created by PhpStorm.
 * User: Korobko
 * Date: 24.11.2017
 * Time: 10:40
 */

namespace App;

use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Auth;

class MenuFilter implements FilterInterface
{
    public function transform($item, Builder $builder)
    {
        if (isset($item['permission']) && !Auth::user()->analyst) {
            return false;
        }

        return $item;
    }
}