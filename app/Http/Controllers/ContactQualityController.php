<?php

namespace App\Http\Controllers;

use App\ContactQuality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContactQualityController extends Controller
{
    public function index()
    {
        if (Auth::user()->role_id != 1){
            redirect('/');
        }

        $contactQuality = ContactQuality::all();

        return view('contact_quality.index', compact('contactQuality'));
    }

    public function addContactQuality(Request $request)
    {

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return response($validator->errors()->all());
            }

        DB::transaction(function () use($request) {
            $contactQuality = new ContactQuality();
            $contactQuality->title = $request->title;
            $contactQuality->description = $request->description;
            $contactQuality->save();
        });

            return response()->make('Квалификация добавлена!');
    }
}
