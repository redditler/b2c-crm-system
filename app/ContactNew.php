<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContactNew extends Model
{

    protected $fillable = [
        'fio', 'region_id', 'city', 'email', 'user_id', 'group_id', 'diler', 'created_at'
    ];

    const CONTACT_GENDER = [
        'Женский',
        'Мужской'
    ];

    protected $table = 'contacts';


    public function group()
    {
        return $this->hasOne('App\UserGroups', 'id', 'group_id');
    }

    public function regions()
    {
        return $this->hasOne('App\Regions', 'id', 'region_id');
    }

    public function contactPhones()
    {
        return $this->hasMany('App\ContactPhones', 'contact_id', 'id');
    }

    public function contactHistory()
    {
        return $this->hasMany('App\ContactHistory', 'client_id', 'id');
    }

    public function contactComment()
    {
        return $this->hasMany('App\ContactComment', 'contact_id', 'id');
    }

    public function contactSources()
    {
        return $this->hasOne('App\CustomerSource', 'id', 'sources_id');
    }

    public function manager()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function contactQuality()
    {
        return $this->belongsTo('App\ContactQuality');
    }

    public function contactPriceCategory()
    {
        return $this->belongsTo('App\ContactPriceCategory', 'price_category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lead()
    {
        return $this->hasMany('App\Leed', 'contact_id', 'id');
    }

    public static function getContact()
    {
        $user = Auth::user();
        $rmManagers = UserRm::getRmManagers()->get();
        $contacts = $user->group_id == 3 ? self::query() :
            self::query()->where('group_id', $user->group_id)
            ->where(function ($q) use($user){
            foreach (UserRegions::getUserRegions() as $region){
                $q->orWhere('region_id', $region->region_id);
            }
        });

        if ($user->role_id == 3) {
            $contacts->where(function ($q) use($user) {
                $q->orWhere('user_id', $user->id);
                $q->orWhere('user_id', 0);
            });
        } elseif ($user->role_id == 4) {
            $contacts->where(function ($q) use ($rmManagers, $user) {
                foreach ($rmManagers as $manager) {
                    $q->orWhere('user_id', $manager->id);
                }
                $q->orWhere('user_id', $user->id);
                $q->orWhere('user_id', 0);

            });
        }

        return $contacts;
    }

    public static function getContactFilter($regions, $dateFrom, $dateTo, $request, $contact_quality_id)
    {
        return ContactNew::getContact()
            ->where(function ($q) use ($regions) {
                foreach ($regions as $value) {
                    $q->orWhere('region_id', $value);
                }
            })
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->where(function ($q) use ($request) {
                if ( $request->phone) {
                    foreach (ContactPhones::getIdLikePhone($request->phone) as $phone) {
                        $q->orWhere('id', $phone->contact_id);
                    }
                }
            })
            ->where(function ($q) use($request){
                if ($request->userId){
                    foreach ($request->userId as $value){
                        $q->orWhere('user_id', $value);
                    }
                }
            })
            ->where( function($q) use($contact_quality_id) {
                foreach($contact_quality_id as $cqid) {
                    $q->orWhere('contact_quality_id', $cqid);
                }
            })
            ->with('group')
            ->with('regions')
            ->with('contactPhones')
            ->with('contactQuality')
            ->with('lead');
    }

    public static function insertNewContact($request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'fio' => 'required|min:2|max:98',
            'region_id' => 'required|integer',
            'phone_primary' => 'required|digits:10|numeric|unique:contact_phones,phone',
            'sources_id' => 'required|integer',
            'group_id' => 'required|integer',
            'city' => 'nullable|string|max:98',
            'comment' => 'nullable|string',
            'email' => 'nullable|email|max:98',
        ], [
            'fio.required' => 'Поле "Ф.И.О." обязательно к заполнению.',
            'fio.min' => 'Минимальный размер "Ф.И.О." содержит не меньше 2х символов.',
            'fio.max' => 'Минимальный размер "Ф.И.О." содержит не больше 98 символов.',
            'region_id.required' => 'Поле "Город" обязательно к заполнению.',
            'region_id.integer' => 'Поле "Город" неверный формат ввода.',
            'phone_primary.required' => 'Поле "Телефон" обязательно к заполнению.',
            'phone_primary.digits' => 'Поле "Телефон" должно содержать 10 цифр.',
            'phone_primary.integer' => 'Поле "Телефон" неверный формат ввода.',
            'sources_id.required' => 'Поле "Источник" обязательно к заполнению.',
            'sources_id.integer' => 'Поле "Источник" неверный формат ввода.',
            'group_id.required' => 'Поле "Группа" обязательно к заполнению.',
            'group_id.integer' => 'Поле "Группа" неверный формат ввода.',
            'city.max' => 'Поле "Адрес" содержит не больше 98 символов.',
            'city.string' => 'Поле "Адрес" неверный формат ввода.',
            'email.max' => 'Поле "E-mail" содержит не больше 98 символов.',
            'email.email' => 'Поле "E-mail" неверный формат ввода.',
            'comment.string' => 'Поле "Комментарий" неверный формат ввода.',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all());
        }

        $contactPhone = ContactPhones::query()->where('phone', $request->phone_primary)->get()->isEmpty();
        if (!$contactPhone){
            return response()->make(['Контакт уже существует!']);
        }

        DB::transaction(function () use ($request, $user) {
            $contact = new Contact();
            $contact->fio = $request->fio;
            $contact->region_id = $request->region_id;
            $contact->city = $request->city;
            $contact->email = $request->email;
            $contact->user_id = $user->id;
            $contact->group_id = $request->group_id;
            $contact->sources_id = $request->sources_id;
            $contact->diler = 0;
            $contact->save();
            $lastId = $contact->id;

            $phoneContact = new ContactPhones();
            $phoneContact->contact_id = $lastId;
            $phoneContact->phone = $request->phone_primary;
            $phoneContact->primary = 1;
            $phoneContact->save();

            if (!empty($request->comment)){
                $contactComment = new ContactComment();
                $contactComment->contact_id = $lastId;
                $contactComment->user_id = $user->id;
                $contactComment->comment = $request->comment;
                $contactComment->save();

                $history = new ContactHistory();
                $history->client_id = $lastId;
                $history->user_id = $user->id;
                $history->description = 'Создан новый клиент, добавлен комментарий пользователем '.$user->name.' клиенту '.$contact->fio.' ID: '.$lastId;
                $history->save();
            }else{
                $history = new ContactHistory();
                $history->client_id = $lastId;
                $history->user_id = $user->id;
                $history->description = 'Клиент создан пользователем '.$user->name.' клиент '.$contact->fio.' ID: '.$lastId;
                $history->save();
            }

        });

       return response()->make('Контакт добавлен!');

    }





}
