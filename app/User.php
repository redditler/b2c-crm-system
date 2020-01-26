<?php

namespace App;

use App\Support\Google2FA;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MailResetPasswordToken;
use DB;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $appends = [
        'role_name',
        'roles_array',
        'groups_array',
        'count_in_today',
        'count_done_today',
    ];


    public function role()
    {
        return $this->hasOne('App\UserRoles', 'id', 'role_id');
    }

    public function group()
    {
        return $this->hasOne('App\UserGroups', 'id', 'group_id');
    }

    public function regionsNew()
    {
        return $this->belongsToMany('App\Regions', 'user_regions', 'user_id', 'region_id')->withTimestamps();
    }

    public function regions()
    {
        return $this->hasMany('App\UserRegions', 'user_id', 'id');
    }

    public function contactComment()
    {
        return $this->hasMany('App\ContactComment', 'user_id', 'id');
    }

    public function contactHistory()
    {
        return $this->hasMany('App\ContactHistory', 'client_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo('App\UserBranches');
    }

    public function google2fa()
    {
        return $this->hasOne('App\Support\Google2FA', 'user_id', 'id');
    }

    public function getCountInTodayAttribute()
    {
        $query = Leed::where('created_at', '>=', DB::raw('CURDATE()'));

        if ($this->chief) {
            $query = $query->whereIn('leed_region_id', $this->regions()->get()->pluck('region_id')->toArray());
            return $query->count();
        } else if ($this->analyst) {
            return $query->count();
        }
    }

    public function getCountDoneTodayAttribute()
    {
        $query = Leed::where('created_at', '>=', DB::raw('CURDATE()'))
            ->where('status_id', '<>', '5')
            ->where('status_id', '<>', '10');

        if ($this->chief) {
            $query = $query->whereIn('leed_region_id', $this->regions()->get()->pluck('region_id')->toArray());
            return $query->count();
        } else if ($this->analyst) {
            return $query->count();
        }
    }

    public function getAnalystAttribute()
    {
        if ($this->role->slug == 'analyst') {
            return TRUE;
        }
        return FALSE;
    }

    public function getChiefAttribute()
    {
        if ($this->role->slug == 'chief') {
            return TRUE;
        }
        return FALSE;
    }

    public function getManagerAttribute()
    {
        if ($this->role->slug == 'manager') {
            return TRUE;
        }
        return FALSE;
    }

    /**
     *  * Send a password reset email to the user
     *  */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token));
    }

    public function getRoleNameAttribute()
    {
        return $this->role->name;
    }

    public function getRolesArrayAttribute()
    {
        return UserRoles::all()->pluck('name', 'id');
    }

    public function getGroupsArrayAttribute()
    {
        return UserGroups::all()->pluck('name', 'id');
    }

    /**
     * @param $region_id
     * @return array
     */
    public function getUsersTelegramByRegion($region_id)
    {
        $t_ids = [];

        $promos = User::select(['users.telegram_id', 'user_regions.id'])
            ->from('user_regions')
            ->leftJoin("users", "user_regions.user_id", "=", "users.id")
            ->where('user_regions.region_id', '=', $region_id)->get();

        foreach ($promos as $promo) {
            if (!is_null($promo->telegram_id)) {
                $t_ids[] = $promo->telegram_id;
            }
        }
        return $t_ids;
    }

    /**
     * @param $region_id
     * @return array
     */
    public function getUsersEmailsByRegion($region_id)
    {
        $emails = [];

        $promos = User::select(['users.email', 'user_regions.id'])
            ->from('user_regions')
            ->leftJoin("users", "user_regions.user_id", "=", "users.id")
            ->where('user_regions.region_id', '=', $region_id)
            ->where('users.role_id', '3')
            ->get();

        foreach ($promos as $promo) {
            $emails[] = $promo->email;
        }
        return $emails;
    }

    public function passwordSecurity()
    {
        return $this->hasOne('App\PasswordSecurity', 'user_id', 'id');
    }


    public static function userManager($groupId = [])
    {
        $user = Auth::user();

        if ($user->role_id == 1) {
            return self::getWorkUser()
                ->whereIn('role_id', [2, 3, 4])
                ->where(function ($q) use ($groupId) {
                    foreach ($groupId as $group) {
                        $q->where('group_id', $group);
                    }
                })
                ->get();
        }
        if ($user->role_id == 2) {
            return self::getWorkUser()
                ->where('group_id', $user->group_id)
                ->where('role_id', 3)
                ->get();
        }
        if ($user->role_id == 3) {
            $regions = UserRegions::getRegions();
            $userInRegion = UserRegions::query()
                ->where(function ($q) use ($regions, $groupId) {
                    foreach ($regions as $region) {
                        $q->orWhere('region_id', $region['region_id']);
                    }
                })->get();

            return self::getWorkUser()
                ->where('role_id', 3)
                ->where(function ($q) use ($userInRegion) {
                    foreach ($userInRegion as $region) {
                        $q->orWhere('id', $region['user_id']);
                    }
                })
                ->get();
        }
        if ($user->role_id == 4) {
            return UserRm::getRmManagers()
                ->where('group_id', Auth::user()->group_id)
                ->where('role_id', 3)
                ->get();
        }
        if ($user->role_id == 5) {
            return self::getWorkUser()
                ->get();
        }

    }

    public static function getUserManagerByFilter(array $groupIds, $roleId, array $regionsIds = [])
    {
        $userInRegion = UserRegions::query()
            ->where(function ($q) use ($regionsIds) {
                foreach ($regionsIds as $region) {
                    $q->orWhere('region_id', $region);
                }
            })->get();

        return self::getWorkUser()
            ->where('role_id', $roleId)
            ->where(function ($q) use ($userInRegion) {
                foreach ($userInRegion as $region) {
                    $q->orWhere('id', $region['user_id']);
                }
            })
            ->where(function ($q) use ($groupIds, $roleId) { //'group_id',
                foreach ($groupIds as $group) {
                    $q->orWhere('group_id', $group);
                    if ($roleId == 5) {
                        $q->orWhere('group_id', 3);
                    }
                }
            })
            ->get();
    }

    public static function getWorkUser($var = 1)
    {
        return self::query()
            ->where('fired', $var);
    }

    //TODO user filter
    public static function preparationUser($var)
    {
        return self::getWorkUser($var['fired'])
            ->where(function ($q) use ($var) {
                if (!empty($var['group'])) {
                    foreach ($var['group'] as $group) {
                        $q->orWhere('group_id', $group);
                    }
                } else {
                    foreach (UserGroups::getUserGroup() as $group) {
                        $q->orWhere('group_id', $group->id);
                    }
                    $q->orWhere('group_id', 0);
                }

            })
            ->where('fired', $var['fired'])
            ->with('role')
            ->with('group')
            ->with('google2fa');
    }

    public static function checkChangeUser2FA($var)
    {
        DB::transaction(function () use ($var) {
            Google2FA::where('user_id', $var->id)->update(['google2fa_enable' => $var->checked == 'true' ? 1 : 0]);
        });
        return Google2FA::where('user_id', $var->id)->first()->google2fa_enable;
    }

    public static function getEventUserTree($user)
    {
        if ($user->role_id == 1) {
            return self::getWorkUser()->get();
        } elseif ($user->role_id == 2) {
            return self::getWorkUser()->where('group_id', $user->group_id)->get();
        } elseif ($user->role_id == 3 || $user->role_id == 5) {
            return self::getWorkUser()->where('id', $user->id)->get();
        } elseif ($user->role_id == 4) {
            return self::getWorkUser()->where(function ($q) use ($user) {
                $q->orWhere('id', $user->id);
                foreach (UserRm::getRmManagers()->get() as $item) {
                    $q->orWhere('id', $item->id);
                }
            })->get();
        }
    }

    public static function getCallCentreUser()
    {
        if(Auth::user()->role_id == 5){
            return User::query()->where('group_id', 1)->orWhere('group_id', 2)->get();
        }
    }



}
