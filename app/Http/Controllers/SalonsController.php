<?php

namespace App\Http\Controllers;

use App\Regions;
use App\UserBranches;
use App\UserGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class SalonsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->manager){
            return redirect('/leeds');
        }
        $branches = UserBranches::query()->with('groups')->get();
        $regions = Regions::all();
        $groups = UserGroups::getUserGroup();
        return view('salons.showSalons', ['branches' => $branches, 'regions' => $regions, 'groups' => $groups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Regions::query()
            ->where('status', 1)
            ->get();
        $groups = UserGroups::getUserGroup();
        return view('salons.createSalon', ['regions' => $regions, 'groups' => $groups]);
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
        if (Auth::user()->manager){
            return redirect('/leeds');
        }
        $valid = Validator::make($request->all(),[
            'slug' => 'required|string|max:60',
            'region' => 'required|string|max:60',
            'name' => 'required|string|max:60',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'code_kb' => 'required|numeric',
            'date_opening' => 'nullable|date'
        ]);

        if ($valid->fails()) {
            $this->throwValidationException(
                $request, $valid
            );
        }else {
            $userBranch = new UserBranches();
            $userBranch->slug = $request['slug'];
            $userBranch->group_id = $request['group'];
            $userBranch->region_id = $request['region'];
            $userBranch->name = $request['name'];
            $userBranch->address = $request['address'];
            $userBranch->phone = $request['phone'];
            $userBranch->code_kb = $request['code_kb'];
            $userBranch->date_opening = $request['date_opening'];
            $userBranch->save();
        }

        return redirect('/salons');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branch = UserBranches::find($id);
        $regions = Regions::query()
            ->where('status', 1)
            ->get();
        $groups = UserGroups::getUserGroup();
        return view('salons.editeSalon', ['branch' => $branch, 'regions' => $regions, 'groups' => $groups]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->manager){
           return redirect('/home');
        }
        $userBranch = UserBranches::find($id);

        $valid = Validator::make($request->all(),[
            'slug' => 'required|string|max:60',
            'region' => 'required|string|max:60',
            'name' => 'required|string|max:60',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'code_kb' => 'required|numeric',
            'date_opening' => 'nullable|date'
        ]);

        if ($valid->fails()) {
            $this->throwValidationException(
                $request, $valid
            );
        }else {
            $userBranch->slug = $request['slug'];
            $userBranch->name = $request['name'];
            $userBranch->group_id = $request['group'];
            $userBranch->region_id = $request['region'];
            $userBranch->address = $request['address'];
            $userBranch->phone = $request['phone'];
            $userBranch->code_kb = $request['code_kb'];
            $userBranch->date_opening = $request['date_opening'];
            $userBranch->save();
        }

        return redirect('/salons');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function setStatus(Request $request)
    {
        if (Auth::user()->role_id != 1){
            return redirect('/');
        }
        $userBranch = UserBranches::find($request->id);
        $userBranch->active ? ($userBranch->active = 0) : ($userBranch->active = 1);
        $userBranch->save();
        return redirect()->back();
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
        if (Auth::user()->role_id != 1){
            return redirect('/');
        }
        UserBranches::find($id)->delete();
        return redirect()->back();
    }
}
