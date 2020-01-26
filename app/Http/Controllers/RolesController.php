<?php

namespace App\Http\Controllers;

use App\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role_id != 1){
            return redirect('/');
        }

        $roles = UserRoles::all();

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            $user = Auth::user();
            if ($user->role_id != 1){
                return redirect('/');
            }

            return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role_id != 1){
            redirect('/');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',

        ], [
            'name.required' => 'Заполните ячейку "Name"',
            'slug.required' => 'Заполните ячейку "Slug"',
        ]);

        if ($validator->fails()) {

            return response($validator->errors()->all());
        }
        try {
            DB::insert('insert into user_roles (slug, name) values (?, ?)',
                [$request->slug, $request->name]);

        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        }

        return response()->make('Role added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        if ($user->role_id != 1){
            redirect('/');
        }
        $role = UserRoles::where('id', $id)->first()->toArray();

        return view('roles.edit', ['role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role_id != 1){
            redirect('/');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',

        ], [
            'name.required' => 'Заполните ячейку "Name"',
            'slug.required' => 'Заполните ячейку "Slug"',
        ]);

        if ($validator->fails()) {

            return response($validator->errors()->all());
        }
        try {
                DB::table('user_roles')
                    ->where('id', $id)->update([
                        'name' => $request->name,
                        'slug' => $request->slug
                        ]);


        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        }

        return response()->make('Role update!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect('/');
    }
}
