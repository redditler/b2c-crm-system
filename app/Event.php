<?php

namespace App;

use App\Http\Controllers\EventController;
use App\Support\LeadFilter\LeadFilterRender;
use App\Support\UserRole\SelectRole;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    const RESULT_EVENT = [
        1 => 'Новое',
        2 => 'В работе',
        3 => 'Выполнено',
        4 => 'Отменено',
    ];

    protected $fillable = ['user_id', 'title', 'user_id_from', 'result', 'start_date', 'end_date'];

    public function users()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function fromUsers()
    {
        return $this->belongsTo('App\User', 'user_id_from', 'id');
    }

    public static function getToDayEvent()
    {
        return self::query()
            ->where(function ($q) {
                $q->orWhere('start_date', Carbon::now()->format('Y-m-d'));
                $q->orWhere('end_date', Carbon::now()->format('Y-m-d'));
            })->where('user_id', Auth::user()->id)->first();
    }

    public static function getEventUser($request)
    {
        $user = Auth::user();
        $filter = LeadFilterRender::chooseFilterMethod($request) ?? false;

        return $data = self::query()
            ->where(function ($q) use ($filter, $user) {
                if ($filter) {
                    foreach ($filter as $value) {
                        $q->orWhere('user_id', $value->id);
                    }
                } else {
                    $q->orWhere('user_id', $user->id);
                }
            })
            ->with('users')
            ->with('fromUsers')
            ->get();
    }
}

