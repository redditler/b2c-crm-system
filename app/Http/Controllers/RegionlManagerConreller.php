<?php

namespace App\Http\Controllers;

use App\User;
use App\UserBranches;
use App\UserRm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegionlManagerConreller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rms = User::where('role_id', 4)
            ->with('group')
            ->get()->toArray();
        $checked = UserRm::all()->toArray();
        $salon = UserBranches::all()->toArray();

        return view('RM.showRm', [
            'rms' => $rms,
            'salon' => $salon,
            'checked' => $checked
        ]);
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
        //
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
        $user = User::find($id)->toArray();
        $branches = UserBranches::query()
            ->with('groups')
            ->get()
            ->toArray();
        $rmtables = UserRm::where('user_id', $id)->get()->toArray();
        $checked =[];
        foreach ($branches as $branch){
            foreach ($rmtables as $rmtable){
                if($branch['id'] == $rmtable['user_branch_id']){
                    $checked[$rmtable['user_branch_id']] = 'checked';
                }
            }
        }
        return view('RM.editeRm', [
            'user' => $user,
            'branches' => $branches,
            'checked' => $checked
        ]);
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
        $auth = Auth::user();
       if (!($auth->role_id > 0 && $auth->role_id < 3)){
           return redirect('/');
       }

        $user = [];
        foreach ($request->all() as $key => $val) {
            if (is_int($key)) {
                $user[] = ['user_id' => $id, 'user_branch_id' => $key];
            }
        }

        $user_rms = UserRm::where('user_id', $id)->get();

        DB::transaction(function () use($user, $user_rms) {
            foreach ($user_rms as $user_rm){
                $user_rm->delete();
            }

            foreach ($user as $val){
                $rm = new UserRm();
                $rm->user_id = $val['user_id'];
                $rm->user_branch_id = $val['user_branch_id'];
                $rm->save();
            }
        });

        return redirect('/rm')->with('Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
