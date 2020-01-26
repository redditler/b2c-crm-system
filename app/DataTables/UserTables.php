<?php
/**
 * Created by PhpStorm.
 * User: pomazan_rn
 * Date: 02.10.2019
 * Time: 15:15
 */

namespace App\DataTables;

use App\User;
use Yajra\DataTables\DataTables;

class UserTables
{
    public static function usersWorkTable($user)
    {
        return DataTables::of(User::preparationUser($user))
            ->orderColumn('id', 'id $1')
            ->addColumn('id', function ($user) {
                return $user->id;
            })
            ->addColumn('name', function ($user) {
                return $user->name.'<br/><i>'.(!empty($user->branch->name) ? $user->branch->name : 'Не установленна').'</i>';
            })
            ->orderColumn('group_id', 'group_id $1')
            ->addColumn('group_id', function ($user) {
                return $user->group->name ?? 'Не установленна';
            })
            ->orderColumn('role_id', 'role_id $1')
            ->addColumn('role_id', function ($user) {
                return $user->role->name ?? 'Не установленна';
            })
            ->addColumn('2fa', function ($user) {
                if (!isset($user->google2fa)){
                    return 'No';
                }
                if ($user->google2fa->google2fa_enable) {
                    return '<input type="checkbox" class="checkboxGoogle2fa" name="google2fa" value="' . $user->id . '" checked>';
                } else {
                    return '<input type="checkbox" class="checkboxGoogle2fa" value="' . $user->id . '"  name="google2fa">';
                }
            })->addColumn('action', function ($user) {
                $fired = $user->fired ? 'Уволить' : 'Вернуть';
                return '<a href="'.route('users.edit', ['user' => $user->id]).'" class="btn btn-sm btn-success editUser">Edit</a>' . ' '
                    . '<button class="btn btn-sm btn-default transferCasesUser" value="' . $user->id . '">Передать дела</button>' . ' '
                    . '<button class="btn btn-sm btn-warning drop2FAUser" value="' . $user->id . '">Reset 2FA</button>' . ' '
                    . '<button class="btn btn-sm btn-danger firedUser" value="' . $user->id . '">'.$fired.'</button>'. ' ';
//                    . '<button class="btn btn-sm btn-danger dropUser" value="' . $user->id . '">Del</button>';
            })
            ->escapeColumns([])
            ->make(true);

    }
}