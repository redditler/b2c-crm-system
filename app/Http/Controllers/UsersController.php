<?php

namespace App\Http\Controllers;

use App\Contact;
use App\ContactComment;
use App\ContactHistory;
use App\ContactNew;
use App\DailyReport;
use App\DataTables\UserTables;
use App\Event;
use App\Group;
use App\Leed;
use App\Regions;
use App\Support\UserRole\SelectRole;
use App\UserBranches;
use App\UserGroups;
use App\UserRegions;
use App\UserRoles;
use App\UsersDirection;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;
use App\User;
use Auth;
use Carbon\Carbon;

class UsersController extends Controller
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
        return view('users.index', ['groups' => UserGroups::all()]);
    }

    public function showUser(Request $request)
    {
        return UserTables::usersWorkTable($request);
    }

    public function changeUser2fa(Request $request)
    {
        return User::checkChangeUser2FA($request);
    }

    public function restartUser2fa(Request $request)
    {
        if (!DB::table('password_securities')->where('user_id', $request->id)){
            return response()->make('Google Authenticator для данного пользователя не найдено');
        }
        DB::transaction(function () use ($request) {
            DB::table('password_securities')->where('user_id', $request->id)->delete();
        });

        return response()->make('Google Authenticator restart');
    }
     public function firedUser(Request $request)
     {
         DB::transaction(function () use ($request) {
             $user = User::find($request->id);
             $user->fired = $user->fired == 1 ? 0 : 1;
             $user->save();
         });

         return response()->make('Сотрудник уволен');
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
        $roles = UserRoles::all()->pluck('name', 'id');
        $branches = UserBranches::all()->pluck('name', 'id');
        return view('users.create-user', compact('groups', 'regions', 'groups', 'roles', 'branches'));
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
            'name' => 'required|max:255',
            'regions' => 'required_without_all',
            'group_id' => 'required',
            'role_id' => 'required',
            'branch_id' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'telegram_id' => 'nullable|sometimes|digits:9',
            'date_employment' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->telegram_id = $request->input('telegram_id');
            $user->date_employment = $request->input('date_employment');
            $user->group_id = $request->input('group_id');
            $user->role_id = $request->input('role_id');
            $user->branch_id = $request->input('branch_id');
            $user->password = bcrypt($request->input('password'));
            $user->save();

            $now = Carbon::now('utc')->toDateTimeString();
            $regions_arr = [];
            foreach ($request->regions AS $key => $region) {
                $region_arr = [
                    'user_id' => $user->id,
                    'region_id' => $region,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                array_push($regions_arr, $region_arr);
            }

            UserRegions::insert($regions_arr);

            return redirect('users')->with('success', 'Successfully created user!');
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
        $user = User::find($id);

        return view('users.show-user')->withUser($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (empty(User::find($id))) {
            return redirect('/users');
        }
        return view('users.edit', [
            'user' => User::find($id),
            'roles' => UserRoles::all(),
            'regions' => Regions::where('status', 1)->get(),
            'groups' => UserGroups::all(),
            'branches' => UserBranches::all()
        ]);

    }

    public function userEditBranch(Request $request)
    {
        return UserBranches::getGroupBranch($request);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'user_name' => 'required|max:255',
//                'regions' => 'required_without_all',
            'user_group' => 'required',
            'user_branch' => 'required',
            'user_role' => 'required',
//                'email' => 'required|email|unique:users,email,' . $user->id,
//                'password' => 'nullable|confirmed|min:6',
            'user_telegram' => 'nullable|sometimes|digits:9',
            'user_date_employment' => 'nullable|date'

        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all());
        }


        DB::transaction(function () use ($request, $user) {

            $user->name = $request->user_name;
            $user->email = $request->user_email;
            $user->telegram_id = $request->user_telegram;
            $user->role_id = $request->user_role;
            $user->group_id = $request->user_group;
            $user->branch_id = $request->user_branch;
            $user->date_employment = $request->user_date_employment;

            $user->regionsNew()->detach();
            $user->regionsNew()->attach($request->user_regions);
            $user->save();

        });
        return response()->make('Пользователь  изменен!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {

        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        if ($currentUser != $user) {
            $user->delete();
            return redirect('users')->with('success', 'Successfully deleted the user!');
        }
        return back()->with('error', 'You cannot delete yourself!');

    }

    public function getUserWitGroup(Request $request)
    {
        return User::query()
            ->where(function ($q) use($request){
                if ( $request->user_group){
                $q->orWhere('group_id', $request->user_group);
                }
            })
            ->get();
    }

    public function userTransfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
        ], [
            'user_id.required' => 'Поле "Выбирите мененджера для передачи дел" обязательно к заполнению.',
            'user_id.integer' => 'Поле "Выбирите мененджера для передачи дел" некорректно.',
            'user_id.exists' => 'Данный менеджер отсутствует.',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all());
        }

        $userOld = User::query()->where('id', $request->old_user_id)->first();
        $userNew = User::query()->where('id', $request->user_id)
            ->with('branch')
            ->first();

        DB::transaction(function () use ($userOld, $userNew) {


            $contacts = ContactNew::query()
                ->where('user_id', $userOld->id)
                ->with('contactHistory')
                ->with('contactComment')
                ->get();

            $leads = Leed::query()
                ->where('user_id', $userOld->id)
                ->get();

            foreach ($contacts as $contact) {
                $contact->user_id = $userNew->id;
                $contact->group_id = $userNew->group_id;
                $contact->region_id = $userNew->branch->region_id;

                ContactComment::create([
                    'contact_id' => $contact->id,
                    'user_id' => $userNew->id,
                    'comment' => 'Перенос контакта от пользователя ' . $userOld->name . ', пользователю  ' . $userNew->name
                ]);
                ContactHistory::create([
                    'client_id' => $contact->id,
                    'user_id' => $userNew->id,
                    'description' => 'Дела переданы:</br> id: ' . $userOld->id . ' ' . $userOld->name . ', => id: ' . $userNew->id . ' ' . $userNew->name,
                ]);
                $contact->save();

            }
            foreach ($leads as $lead) {
                $lead->leed_region_id = $userNew->branch->region_id;
                $lead->user_id = $userNew->id;
                $lead->comment = 'Перенос контакта от пользователя ' . $userOld->name . ' к  ' . $userNew->name;
                $lead->save();
            }
        });


        return response()->make('Трансфер выполнен');

    }

    public function employees()
    {
        return view('users.support.employees', ['employees' => SelectRole::selectRole(Auth::user())->getUserChildren()]);
    }


}
