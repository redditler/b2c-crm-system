<?php

namespace App\Http\Controllers;

use App\LeedReceive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadReceiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leadReceive = LeedReceive::all();
        return view('leadReceive.index', compact('leadReceive'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('leadReceive.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::transaction(function() use($request){
            $receive = new LeedReceive();
            $receive->title = $request['title'];
            $receive->slug = $request['slug'];
            $receive->save();
        });

        return redirect('leadReceive');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('leadReceive');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $receive = LeedReceive::query()->where('id', $id)->first()->toArray();
        return view('leadReceive.edit', compact('receive'));
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
        DB::transaction(function() use($request, $id){
           LeedReceive::where('id', $id)
                ->update([
                    'title' => $request['title'],
                    'slug' => $request['slug']
                ]);
        });

        return redirect('leadReceive');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect('leadReceive');
    }
}
