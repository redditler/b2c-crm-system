<?php

namespace App\Http\Controllers;

use App\CustomerSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerSourceController extends Controller
{
    public function index()
    {
        $sources = CustomerSource::query()->get();

        return view('customerSource.index', compact('sources'));
    }

    public function addSources(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:90',
            'alias' => 'required|min:2|max:90',
            'description' => 'required',
        ], [
            'name.required' => 'Поле <b>"Название источника"</b> обязательно к заполнению.<br/>',
            'name.unique' => 'Поле <b>"Название источника"</b> уникальное.<br/>',
            'name.min' => 'Минимальный размер <b>"Название источника"</b> содержит не меньше 2х символов.<br/>',
            'name.max' => 'Минимальный размер <b>"Название источника"</b> содержит не больше 98 символов.<br/>',
            'description.required' => 'Поле <b>"Описание источника"</b> обязательно к заполнению.<br/>',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all());
        }


        DB::transaction(function () use ($request){
            $sources = new CustomerSource();
            $sources->name = $request->name;
            $sources->alias = $request->alias;
            $sources->description = $request->description;
            $sources->save();
        });

        return response()->make('Источник клиентов добавлен!');
    }

    public function showEditSources($id)
    {
        $sources = CustomerSource::query()->where('id', $id)->first();

        return $sources;
    }

    public function editSources(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:90',
            'alias' => 'required|min:2|max:90',
            'description' => 'required',
        ], [
            'name.required' => 'Поле <b>"Название источника"</b> обязательно к заполнению.<br/>',
            'name.unique' => 'Поле <b>"Название источника"</b> уникальное.<br/>',
            'name.min' => 'Минимальный размер <b>"Название источника"</b> содержит не меньше 2х символов.<br/>',
            'name.max' => 'Минимальный размер <b>"Название источника"</b> содержит не больше 90 символов.<br/>',
            'description.required' => 'Поле <b>"Описание источника"</b> обязательно к заполнению.<br/>',
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all());
        }

        DB::transaction(function () use ($request){
            $sources = CustomerSource::query()->where('id', $request->id)->first();
            $sources->name = $request->name;
            $sources->alias = $request->alias;
            $sources->description = $request->description;
            $sources->save();
        });

        return response()->make('Источник клиентов изменен!');
    }


}
