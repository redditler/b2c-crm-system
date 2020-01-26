<?php

namespace App\Http\Controllers;

use App\Contact;
use App\ContactComment;
use App\ContactHistory;
use App\ContactNew;
use App\ContactPhones;
use App\ContactQuality;
use App\CustomerSource;
use App\Leed;
use App\Messangers;
use App\Regions;
use App\Support\ExportXlsx;
use App\User;
use App\UserGroups;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ContactNewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contacts.index');
    }

    public function indexShow(Request $request)
    {
        $regions = [];
        $dateInit = Leed::dateFromLead();

        $dateFrom = $dateInit[0];
        $dateTo = $dateInit[1];
        $contact_quality_id = $request->get('qualityId') ? : [];

        if ($request->has('regions')) {
            $regions = $request->get('regions');
        }
        if($request->has('ContactDateFrom') || $request->has('ContactDateTo')) {
            $dateFrom = $request->ContactDateFrom;
            $dateTo = $request->ContactDateTo;
        }

        $contact = ContactNew::getContactFilter($regions, $dateFrom, $dateTo, $request, $contact_quality_id);

        return Datatables::of($contact)
            ->orderColumn('id', 'id $1')
            ->addColumn('id', function ($contact) {
                return $contact->id;
            })
            ->orderColumn('name', 'fio $1')
            ->addColumn('name', function ($contact) {
                return $contact->fio;
            })
            ->orderColumn('phone', 'contacts.id $1')
            ->addColumn('phone', function ($contact) {
                foreach ($contact->contactPhones as $value) {
                    if ($value->primary == 1) {
                        return $value->phone;
                    } else {
                        return $value->phone;
                    }
                }
            })
            ->addColumn('status', function($contact) {
                foreach( $contact->lead as $lead){
                    return $lead['status_id'];
                }
            })
            ->addColumn('contact_quality', function ($contact) {
                return $contact->contact_quality_id ? $contact->contactQuality->title : 'Не установлен';
            })
            ->orderColumn('group', 'group_id $1')
            ->addColumn('group', function ($contact) {
                return $contact->group_id ? $contact->group->name : 'Не установленна';
            })
            ->orderColumn('region', 'region_id $1')
            ->addColumn('region', function ($contact) {
                return $contact->region_id ? $contact->regions->name : 'Не установлен';
            })
            ->orderColumn('user', 'user_id $1')
            ->addColumn('user', function ($contact) {
                return $contact->user_id ? $contact->manager->name : 'Не распределен';
            })
            ->orderColumn('data', 'contacts.created_at $1')
            ->addColumn('data', function ($contact) {
                return Carbon::make($contact->created_at)->format('d-m-Y');
            })
            ->escapeColumns([])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        return ContactNew::insertNewContact($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = ContactNew::getContact()
            ->where('id', $id)
            ->with('contactPhones')
            ->firstOrFail();
        $genders = ContactNew::CONTACT_GENDER;

        return view('contacts.contactEdit', ['contact' => $contact, 'genders' => $genders]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
//        return response()->make($request->all());

        if ($request->contactFormEdit) {
            $validator = Validator::make($request->all(), [
                'fio' => 'required|min:2|max:98',
                'age' => 'integer',
                'gender' => 'required|integer|min:0|max:1',
                'region_id' => 'required|integer',
                'city' => 'nullable|string|max:98',
                'email' => 'nullable|email|max:98',
                'sources_id' => 'integer',
            ], [
                'fio.required' => 'Поле "Ф.И.О." обязательно к заполнению.',
                'fio.min' => 'Минимальный размер "Ф.И.О." содержит не меньше 2х символов.',
                'fio.max' => 'Минимальный размер "Ф.И.О." содержит не больше 98 символов.',
                'region_id.required' => 'Поле "Город" обязательно к заполнению.',
                'region_id.integer' => 'Поле "Город" неверный формат ввода.',
                'age.integer' => 'Поле "Возраст" только числовое значение.',
                'gender.integer' => 'Поле "Пол" неверный формат ввода.',
                'gender.required' => 'Поле "Пол" не опеределен.',
                'city.max' => 'Поле "Адрес" содержит не больше 98 символов.',
                'city.string' => 'Поле "Адрес" неверный формат ввода.',
                'email.max' => 'Поле "E-mail" содержит не больше 98 символов.',
                'email.email' => 'Поле "E-mail" неверный формат ввода.',
                'sources_id.integer' => 'Поле "Источник клиента" неверный формат ввода.',


            ]);

            if ($validator->fails()) {
                return response($validator->errors()->all());
            }

            DB::transaction(function () use ($request, $user, $id) {

                $oldClient = ContactNew::query()
                    ->where('id', $id)
                    ->with('regions')
                    ->first();

                $region = Regions::query()
                    ->where('id', $request->region_id)
                    ->first();

                $CONTACT_GENDER = ContactNew::CONTACT_GENDER;
                $oldGender = !is_null($oldClient->gender) ? $CONTACT_GENDER[$oldClient->gender] : 'не указан';
                $sources = $request->sources_id ? CustomerSource::where('id', $request->sources_id)->first()->name : 'Не определенно';

                $oldRegion = !is_null($oldClient->region_id) ? $oldClient->regions->name : 'не указан';
                $quality = $request->contact_quality_id ? ContactQuality::where('id', $request->contact_quality_id)->first()->title : 'Не определенно';

                $oldQuality = ($oldClient->contact_quality_id) ? $oldClient->contactQuality->title : 'Не указано';
                $oldSources = ($oldClient->sources_id) ? $oldClient->contactSources->name : 'Не указано';

                $contactHistory = new ContactHistory();
                $contactHistory->client_id = $id;
                $contactHistory->user_id = $user->id;
                $contactHistory->description = "Пользователь " . $user->name . ' внес изменения в клиента ' . $oldClient->fio . ' ID: ' . $id . ' :<br/>
            Ф.И.О.: ' . $oldClient->fio . ' -> ' . $request->fio . '<br/>
            Возраст: ' . $oldClient->age . ' -> ' . $request->age . '<br/>
            Пол: ' . $oldGender . ' -> ' . $CONTACT_GENDER[$request->gender] . '<br/>
            Регион: ' . $oldRegion . ' -> ' . $region->name . '<br/>
            Адрес: ' . $oldClient->city . ' -> ' . htmlspecialchars($request->city) . '<br/>
            E-mail: ' . $oldClient->email . ' -> ' . $request->email . '<br/>
                Квалификация клиента: ' . $oldQuality . ' -> ' . $quality . '<br/>
                Источник клиента: ' . $oldSources . ' -> ' . $sources;
                $contactHistory->save();

                ContactNew::
                where('id', $id)
                    ->update([
                        'fio' => $request->fio,
                        'age' => $request->age,
                        'gender' => $request->gender,
                        'region_id' => $request->region_id,
                        'city' => htmlspecialchars($request->city),
                        'email' => $request->email,
                        'sources_id' => $request->sources_id,
                        'contact_quality_id' => $request->contact_quality_id,
                        'price_category_id' => $request->price_category_id,
                        'updated_at' => Carbon::now()
                    ]);


            });
            return response()->make('Контакт Изменен!');

        } elseif ($request->contactAdditionalFormEdit) {

            $validator = Validator::make($request->all(), [
                'group_id' => 'required|integer',
                'user_id' => 'required|integer',
            ], [
                'group_id.required' => 'Поле "Группа" обязательно к заполнению.',
                'group_id.integer' => 'Поле "Группа" неверный формат ввода.',
                'user_id.required' => 'Поле "Ответственный менеджер" обязательно к заполнению.',
                'user_id.integer' => 'Поле "Ответственный менеджер" неверный формат ввода.',

            ]);

            if ($validator->fails()) {
                return response($validator->errors()->all());
            }

            DB::transaction(function () use ($request, $user, $id) {

                $oldClient = ContactNew::query()
                    ->where('id', $id)
                    ->first();

                $manager = User::where('id', $request->user_id)->first();
                $userGroup = UserGroups::where('id', $request->group_id)->first();

                $oldGroup = !is_null($oldClient->grup_id) ? $oldClient->group->name : 'Не указано';
                $oldManager = ($oldClient->user_id) ? $oldClient->manager->name : 'Не указано';


                $contactHistory = new ContactHistory();
                $contactHistory->client_id = $id;
                $contactHistory->user_id = $user->id;
                $contactHistory->description = 'Пользователь ' . $user->name . ' внес изменения в клиента ' . $oldClient->fio . ' ID: ' . $id . ':<br/> 
                Группа: ' . $oldGroup . ' -> ' . $userGroup->name . '<br/>
                Менеджер: ' . $oldManager . ' -> ' . $manager->name;
                $contactHistory->save();

                $contactNew = ContactNew::where('id', $id)->with('lead')->first();
                $contactNew->user_id = $request->user_id;
                $contactNew->group_id = $request->group_id;
                $contactNew->sources_id = $request->sources_id;
                $contactNew->contact_quality_id = $request->contact_quality_id;
                $contactNew->updated_at = Carbon::now();

                foreach ($contactNew->lead as $lead) {
                    $lead->user_id = $request->user_id;
                    $lead->leed_region_id = User::where('id', $request->user_id)->first()->branch->region_id;
                }

                $contactNew->push();


            });

            return response()->make('Контакт изменен!');

        } elseif ($request->contactFormPhone) {

            $validator = Validator::make($request->all(), [
                'secondPhone' => 'required|digits:10|numeric|unique:contact_phones,phone',
            ], [
                'secondPhone.required' => 'Поле "Добавить телефон" обязательно к заполнению.',
                'secondPhone.numeric' => 'Поле "Добавить телефон" неверный формат ввода.',
                'secondPhone.unique' => 'Поле "Добавить телефон" такой телефон уже существует.',
                'secondPhone.digits' => 'Поле "Добавить телефон" должно содержать 10 чисел.',
            ]);

            if ($validator->fails()) {
                return response($validator->errors()->all());
            }


            DB::transaction(function () use ($request, $id, $user) {

                $oldClient = ContactNew::where('id', $id)
                    ->first();

                $history = new ContactHistory();
                $history->client_id = $id;
                $history->user_id = $user->id;
                $history->description = $user->name . ' добавил к.т. ' . $request->secondPhone . ' пользователю ' . $oldClient->fio . ' ID: ' . $id;
                $history->save();

                $phone = new ContactPhones();
                $phone->contact_id = $id;
                $phone->phone = $request->secondPhone;
                if ($request->primary) {
                    $phone->primary = 1;
                    $elsePhone = ContactPhones::where('contact_id', $id)->get();
                    foreach ($elsePhone as $value) {
                        $value->update(['primary' => 0]);
                    }
                }
                $phone->save();

            });

            return response()->make('Контакт изменен!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $phone = ContactPhones::query()->where('id', $id)->first();
        $contactId = $phone->contact_id;

        $phoneContact = ContactPhones::query()->where('contact_id', $contactId)->get();

        if (count($phoneContact) == 1) {
            return response()->make('Контакт не может быть удален!');

        } elseif (count($phoneContact) >= 2) {
            if ($phone->primary) {

                DB::transaction(function () use ($contactId, $phone, $user) {

                    $history = new ContactHistory();
                    $history->client_id = $contactId;
                    $history->user_id = $user->id;
                    $history->description = $user->name . ' удалил телефон ' . $phone->phone . ' у клинта ID: ' . $contactId . '/ ' . $phone->contactNew->fio;
                    $history->save();

                    ContactPhones::where('contact_id', $contactId)
                        ->where('phone', '!=', $phone->phone)
                        ->first()
                        ->update(['primary' => 1]);

                    $phone->delete();

                });
                return response()->make('Телефон удален!');
            } else {
                DB::transaction(/**
                 *
                 */
                    function () use ($id, $user, $contactId, $phone) {

                        $history = new ContactHistory();
                        $history->client_id = $contactId;
                        $history->user_id = $user->id;
                        $history->description = $user->name . ' удалил телефон ' . $phone->phone . ' у клиента ID: ' . $contactId . ' / ' . $phone->contactNew->fio;
                        $history->save();

                        ContactPhones::where('id', $id)->delete();
                    });

                return response()->make('Телефон удален!');
            }
        }


    }

    public function showComment(Request $request)
    {
        $comment = ContactComment::clientComment($request->id)->with('user');

        return Datatables::of($comment)
            ->orderColumn('user_id', 'user_id $1')
            ->addColumn('user_id', function ($comment) {
                return $comment->user_id != 0 ? $comment->user->name : 'Сайт компании';
            })
            ->orderColumn('created_at', 'created_at $1')
            ->addColumn('created_at', function ($comment) {
                return $comment->created_at->format('d-m-Y H:i:s');
            })
            ->addColumn('comment', function ($comment) {
                return $comment->comment;
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function showHistory(Request $request)
    {
        $history = ContactHistory::clientHistory($request->id)->with('user');

        return Datatables::of($history)
            ->orderColumn('user_id', 'user_id $1')
            ->addColumn('user_id', function ($history) {
                return $history->user_id != 0 ? $history->user->name : 'Сайт компании';
            })
            ->orderColumn('created_at', 'created_at $1')
            ->addColumn('created_at', function ($history) {
                return $history->created_at->format('d-m-Y H:i:s');
            })
            ->addColumn('description', function ($history) {
                return $history->description;
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function addContactComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'comment' => 'required',
        ], [
            'id.required' => 'Поле "Ф.И.О." обязательно к заполнению.',
            'comment.required' => 'Поле "Комментарий" обязательно к заполнению.',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all());
        }

        $user = Auth::user();
        $client = Contact::query()->where('id', $request->id)->first();

        DB::transaction(function () use ($request, $user, $client) {
            $comment = new ContactComment();
            $comment->contact_id = $request->id;
            $comment->user_id = $user->id;
            $comment->comment = htmlspecialchars($request->comment);
            $comment->save();
            //$lastId = $comment->id;
            $history = new ContactHistory();
            $history->client_id = $request->id;
            $history->user_id = $user->id;
            $history->description = 'Добавлен комментарий пользователем ' . $user->name . ' клиенту ' . $client->fio;
            $history->save();

        });

        return response()->make('Комментарий добавлен!');
    }

    public function showContactPhone(Request $request)
    {

        $phone = ContactPhones::query()
            ->where('contact_id', $request->id)->with('messangers');

        return $phone->get();

    }

    public function contactPhoneUpdate(Request $request)
    {
        $validPhone = '';

        if ($request->phone == ContactPhones::phoneNumber($request->id)) {
            $validPhone = 'required|digits:10|numeric';
        } else if(!empty($request->id)) {
            $validPhone = 'required|digits:10|numeric|unique:contact_phones,phone';
        }

        $data = $request->all();
        if(empty($request->id) && isset($request->contact_id)) {

            $insertId = ContactPhones::addContactPhone($data);
            foreach($data['messangers'] as &$messanger){
                $messanger['phone_id'] = $insertId;
            }
        } else if($request->id && $request->phone == null) {
            Messangers::removeeMessanger($data);
            ContactPhones::removeContactPhone($data['id']);
        }

        if(!empty($data['messangers'])){
            Messangers::addMessanger($data);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'phone' => $validPhone,
            'primary' => 'required|numeric',
        ], [
            'id.required' => 'Вмешательство в работу системы.',
            'phone.required' => 'Поле "Телефон" обязательно к заполнению.',
            'phone.digits' => 'Поле "Телефон" содержит 10 знаков.',
            'phone.numeric' => 'Поле "Телефон" заполняется только цифрами.',
            'phone.unique' => 'Введенный телефон уже существует.',
            'primary.required' => 'Вмешательство в работу системы.',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all());
        }

        $phone = ContactPhones::query()->where('id', $request->id)->first();
        $contactId = $phone->contact_id;
        $user = Auth::user();

        if ($request->primary) {

            DB::transaction(function () use ($request, $contactId, $user, $phone) {
                $oldPhone = $phone->phone;

                $history = new ContactHistory();
                $history->client_id = $contactId;
                $history->user_id = $user->id;
                $history->description = $user->name . 'изменил телефон ' . $oldPhone . ' изменен на ' . $request->phone . ', тедефону ' . $request->phone . ' присвоен статус основной у клиента ID: ' . $contactId . ' / ' . $phone->contactNew->fio;
                $history->save();

                if (count(ContactPhones::query()->where('contact_id', $contactId)->get()) > 1) {
                    $zeroPrime = ContactPhones::where('contact_id', $contactId)
                        ->where('primary', 1)
                        ->first();
                    $zeroPrime->primary = 0;
                    $zeroPrime->save();
                }

                $newPhone = ContactPhones::where('id', $request->id)
                    ->where('contact_id', $contactId)
                    ->first();
                $newPhone->phone = $request->phone;
                $newPhone->primary = 1;
                $newPhone->save();

            });

            return response()->make('Телефонный номер изменен');

        } else {

            DB::transaction(function () use ($request, $contactId, $user, $phone) {
                $oldPhone = $phone->phone;

                $history = new ContactHistory();
                $history->client_id = $contactId;
                $history->user_id = $user->id;
                $history->description = $user->name . 'изменил телефон ' . $oldPhone . ' изменен на ' . $request->phone . ' у клиента ID: ' . $contactId . ' / ' . $phone->contactNew->fio;
                $history->save();

                $newPhone = ContactPhones::where('id', $request->id)
                    ->where('contact_id', $contactId)
                    ->first();

                $newPhone->phone = $request->phone;
                $newPhone->save();

            });
            return response()->make('Телефонный номер изменен');
        }

    }

    public function exportXls(Request $request)
    {
        return view('exportXlsFile.clientXls', ['request' => $request]);

    }



}
