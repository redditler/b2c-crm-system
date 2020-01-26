<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactComment extends Model
{
    protected $fillable = [
        'contact_id', 'user_id', 'comment'
    ];

    protected $table = 'contact_comments';

    public static function clientComment($id)
    {
        return self::query()->where('contact_id', $id);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo('App\ContactNew', 'contact_id', 'id');
    }
}
