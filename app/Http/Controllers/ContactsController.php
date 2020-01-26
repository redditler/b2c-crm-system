<?php

namespace App\Http\Controllers;

use App\Contact;
use App\ContactPhones;
use App\Group;
use App\Leed;
use App\Regions;
use App\UserGroups;
use App\UserRegions;
use App\UserRoles;
use App\UsersDirection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use App\User;
use Auth;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use DB;
use Illuminate\Validation\Rule;


class ContactsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contacts.show-contacts');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Regions::all()->pluck('name', 'id');
        $groups = UserGroups::all()->pluck('name', 'id');
        $users = User::where('role_id', '=', '3')->pluck('name', 'id');
        return view('contacts.create-contact', compact('groups', 'regions', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'fio' => 'required|max:255',
            'city' => 'required|max:255',
            'region_id' => 'required',
            'group_id' => 'required',
            'user_id' => 'required',
            'comment' => 'nullable|sometimes|max:1024',
            'email' => 'required|email|max:255|unique:contacts',
            'phone' => 'required|digits:10|unique:contact_phones|unique:contact_phones',
            'phones.*' => 'nullable|sometimes|digits:10|unique:contact_phones,phone, phone'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {
            $contact = new Contact;
            $contact->fill($request->all());
            $contact->save();
            $now = Carbon::now('utc')->toDateTimeString();
            $phones_arr = [];
            $phone = [
                'contact_id' => $contact->id,
                'phone' => $request->phone,
                'created_at' => $now,
                'updated_at' => $now
            ];
            array_push($phones_arr, $phone);
            if (isset($request->phones)) {
                foreach ($request->phones AS $key => $phone) {
                    $phone_arr = [
                        'contact_id' => $contact->id,
                        'phone' => $phone,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                    if (!empty($phone)) {
                        array_push($phones_arr, $phone_arr);
                    }
                }
            }

            ContactPhones::insert($phones_arr);

            return redirect('contacts')->with('success', 'Контакт добавлен!');
        }
    }

    public function historyByPhone($leed_id)
    {
        $leed = Leed::find($leed_id);
        $phone = ContactPhones::where('phone', '=', $leed->leed_phone)->first();
        if ($phone) {
//            $this->show($contact->id);
            return redirect('/contacts/' . $phone->contact_id);
        } else {
            $msg = 'Контакт не найден.';
            return view('errors.404')->withMsg($msg);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::with('phones')->find($id);

        return view('contacts.show-contact')->withContact($contact);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        $regions = Regions::all()->pluck('name', 'id');
        $groups = UserGroups::all()->pluck('name', 'id');
        $users = User::where('role_id', '=', '3')->pluck('name', 'id');

        return view('contacts.edit-contact', compact('contact', 'regions', 'groups', 'users'));


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
        $contact = Contact::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'fio' => 'required|max:255',
            'city' => 'nullable|sometimes|max:255',
            'region_id' => 'nullable|sometimes',
            'group_id' => 'required',
            'user_id' => 'required',
            'email' => 'nullable|sometimes|email|max:255|unique:contacts,email,' . $contact->id,
            'phone' => 'required|digits:10|unique:contact_phones,phone,' . $contact->phones->pluck('id')->first(),
            'phones.*' => ['nullable',
                'sometimes',
                'digits:10',
                Rule::unique('contact_phones', 'phone')->ignore($contact->id, 'contact_id'),
            ]
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {

            $contact->fill($request->all());
            $contact->save();
            $now = Carbon::now('utc')->toDateTimeString();
            $phones_arr = [];
            $phone = [
                'contact_id' => $contact->id,
                'phone' => $request->phone,
                'created_at' => $contact->created_at,
                'updated_at' => $now
            ];
            array_push($phones_arr, $phone);
            if (isset($request->phones)) {
                foreach ($request->phones AS $key => $phone) {
                    $phone_arr = [
                        'contact_id' => $contact->id,
                        'phone' => $phone,
                        'created_at' => $contact->created_at,
                        'updated_at' => $now
                    ];
                    if (!empty($phone)) {
                        array_push($phones_arr, $phone_arr);
                    }
                }
            }

            ContactPhones::where('contact_id', '=', $contact->id)->delete();

            ContactPhones::insert($phones_arr);
        }
        return back()->with('success', 'Successfully updated contact');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        $contact_phones = ContactPhones::where('contact_id', '=', $id)->delete();
        return redirect('contacts')->with('success', 'Successfully deleted the contact!');

    }

    public function getContacts(Request $request)
    {
        $date_from = isset($_POST["date_from"]) ? $_POST["date_from"] : NULL;
        $date_to = isset($_POST["date_to"]) ? $_POST["date_to"] : NULL;

        $contacts = Contact::select(['contacts.id', 'contacts.fio', 'contacts.comment', 'contacts.city', 'contacts.email', 'contacts.user_id', 'contacts.diler', 'users.name'])
//            ->leftJoin('contact_phones', 'contacts.id','=','contact_phones.contact_id')
            ->leftJoin('users', 'contacts.user_id', '=', 'users.id');


        if (!empty($date_from) && !empty($date_to)) {
            $leeds = Leed::whereBetween('created_at', [$date_from, $date_to]);
        } elseif (!empty($date_from)) {
            $leeds = Leed::where('created_at', '>=', $date_from);
        } elseif (!empty($date_to)) {
            $leeds = Leed::where('created_at', '<=', $date_to);
        }
        if (!empty($date_from) || !empty($date_to)) {
            $leeds = $leeds->groupBy('leed_phone')->pluck('leed_phone')->toArray();
//            dd($leeds);
            $contacts = $contacts->whereHas('phones', function ($q) use ($leeds) {
                $q->whereIn('phone', $leeds);
            });
        }


//        dd($request->all());
        return Datatables::of($contacts)
            ->addColumn('last_call', function ($contact) {
                $phone = ContactPhones::where('contact_id', '=', $contact->id)->pluck('phone')->toArray();
                $leed = Leed::whereIn('leed_phone', $phone)->orderBy('created_at', 'desc')->first();
                if ($leed) {
                    return $leed->created_at;
                } else {
                    return '';
                }
            })
            ->addColumn('manager', function ($contact) {
                if (!empty($contact->user_id)) {
                    return $contact->manager->name;
                } else {
                    return '';
                }
            })
            ->addColumn('phone', function ($contact) {
                $phone = ContactPhones::where('contact_id', '=', $contact->id)->orderBy('id', 'asc')->first();
                return $phone->phone;
            })
            ->filterColumn('manager', function ($query, $keyword) {
                $sql = "users.name  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('phone', function ($query, $keyword) {
                $query->whereHas('phones', function ($q) use ($keyword) {
                    $q->where('phone', 'like', '%' . $keyword . '%');
                });
            })
            ->editColumn('btn', function ($contact) {
                return '<a href="/contacts/' . $contact->id . '" class="btn btn-danger btn-xs contasts_danger" data-toggle="tooltip" data-placement="bottom" title="Перейти к контакту"><img src="/img/user_more.png"/></a>';
            })
            ->order(function ($query) {
                if (request()->has('order') && request()->order[0]['column'] == '2') {
                    $query->load(['manager' => function ($query) {
                        $query->orderBy('name', 'asc');
                    }]);
//                    $query->orderBy('users.name', request()->order[0]['dir']);
                }

//                if (request()->has('order') && request()->order[0]['column'] == '2' && request()->order[0]['dir'] == 'desc') {
//                    $query->orderBy('users.name', 'desc');
//                }

//                if (request()->has('email')) {
//                    $query->orderBy('email', 'desc');
//                }
            })
            ->escapeColumns([])
            ->make();
    }
}
