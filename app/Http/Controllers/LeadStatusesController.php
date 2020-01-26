<?php

namespace App\Http\Controllers;

use App\LeedStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeadStatusesController extends Controller
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
        $leadStatuses = LeedStatus::all()->toArray();

        return view('leadStatus.index', compact('leadStatuses'));
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
        return view('leadStatus.create');
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
            return redirect('/');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
        ], [
            'name.required' => 'Введіть назву статуса',
            'slug.required' => 'Введіть slug'
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all());
        }
        try {
            DB::insert('insert into leed_statuses (slug, name) values (?, ?)',
                [$request->slug, $request->name]);

        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        }

        return response()->make('Додано новий статус!');
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
            return redirect('/');
        }

        $leadStatus = LeedStatus::where('id', $id)->first()->toArray();

        return view('leadStatus.edit', compact('leadStatus'));
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
            return redirect('/');
        }

        $validator = Validator::make($request->all(), [
            'slug' => 'required',
            'name' => 'required'
        ], [
            'name.required' => 'Введіть назву статусу',
            'slug.required' => 'Введіть slug',

        ]);

        if ($validator->fails()) {

            return response($validator->errors()->all());
        }

        try {
            DB::table('leed_statuses')
                ->where('id', $id)
                ->update([
                    'slug' => $request->slug,
                    'name' => $request->name,
                ]);

        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        }

        return response()->make('Статус змінено!');
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
