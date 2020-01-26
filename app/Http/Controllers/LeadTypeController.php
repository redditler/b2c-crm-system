<?php

namespace App\Http\Controllers;

use App\LeedType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leadType = LeedType::all();
        return view('leadType.index', compact('leadType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('leadType.create');
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
            $receive = new LeedType();
            $receive->title = $request['title'];
            $receive->slug = $request['slug'];
            $receive->save();
        });

        return redirect('leadType');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('leadType');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leadType = LeedType::where('id', $id)->first()->toArray();

        return view('leadType.edit', compact('leadType'));
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
            LeedType::where('id', $id)
                ->update([
                    'title' => $request['title'],
                    'slug' => $request['slug']
                ]);
        });

        return redirect('leadType');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect('leadType');
    }
}
