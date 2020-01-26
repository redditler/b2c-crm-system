<?php

namespace App\Http\Controllers;

use App\Regions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegionsController extends Controller
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
        $regions = Regions::all()->toArray();

        return view('regions.index', ['regions' => $regions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('regions.create');
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
        ], [
            'name.required' => 'Введіть назву регіону'
        ]);

        if ($validator->fails()) {

            return response($validator->errors()->all());
        }
        $region_order = Regions::select('region_order')->max('region_order') +1;
        $status = isset($request->status) ? 1 : 0;

        try {
            DB::insert('insert into regions (name,region_order, status) values (?, ?, ?)',
                [$request->name,  $region_order, $status]);

        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        }

        return response()->make('Region added!');

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

        $region = Regions::where('id', $id)->first()->toArray();

        return view('regions.edit', ['region' => $region]);
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
            'name' => 'required',
            'region_order' => 'required|numeric'
        ], [
            'name.required' => 'Введіть назву регіону',
            'region_order.required' => 'Введіть порядковій номер',
            'region_order.numeric' => 'Тыльки числовы значення',

        ]);

        if ($validator->fails()) {

            return response($validator->errors()->all());
        }
        $status = isset($request->status) ? 1 : 0;

        try {
            DB::table('regions')
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'region_order' => $request->region_order,
                    'status' => $status
            ]);

        } catch (Exception $e) {
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
        }

        return response()->make('Region added!');

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

    public function changeApi(Request $request)
    {
        //return $request->all();
        return Regions::checkChangeApi($request);
    }
}
