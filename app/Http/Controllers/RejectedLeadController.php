<?php

namespace App\Http\Controllers;

use App\Leed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RejectedLeadController extends Controller
{
    public $user;

    public function rejectTrue(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'comment' => 'required|string|min:5',


        ], [
            'id.required' => 'Поле Id не должно быть пустым',
            'id.integer' => 'Поле Id должно содержать числовое значение',
            'comment.required' => 'Поле "Комментарий" не должно быть пустым',
            'comment.min' => 'Поле "Комментарий" должно содержать не меньше 5 символов',
        ]);

        if ($validator->fails()) {

            return response($validator->errors()->all());
        }

        $this->user = Auth::user();

        if ($this->user->role_id != 3 ){
            return redirect('/');
        }

        DB::transaction(function () use ($request) {

        Leed::where('id', $request->id)
            ->update(['user_id' => $this->user->id,'rejected_lead' => 1, 'comment' => $request->comment]);

        });

        return response()->make('Лид отменен');

    }

    public function rejectFalse(Request $request)
    {
        $this->user = Auth::user();

        if ($this->user->role_id == 3 || $this->user->role_id == 5  ){
            return redirect('/');
        }

        Leed::where('id', $request->id)->update(['rejected_lead' => 0]);
        return redirect()->back();
    }
}
