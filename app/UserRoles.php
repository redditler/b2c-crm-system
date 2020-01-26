<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    public static function getRoles()
    {
        return self::query()
            ->whereIn('id', [4,3])
            ->get()->toArray();
    }

    public static function getRolesById(array $ids)
    {
        return self::query()
            ->whereIn('id', $ids)
            ->get()->toArray();
    }
}
