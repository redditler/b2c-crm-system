@extends('adminlte::page')

@section('content_header')
    <div class="title-page">
        <h1 class="title-page__name">Групы</h1>
    </div>
@endsection

@section('content')
    <div class="row margin">
        <div class="col-md-1">
            <a href="{{route('groups.create')}}" class="btn btn-success btn-sm">Добавить</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <table class="table table-bordered bg bg-gray-light">
                <thead>
                <tr class="text-center">
                    <td>ID</td>
                    <td>Name</td>
                    <td>Slug</td>
                    <td>Action</td>
                </tr>
                </thead>
                <tbody>
                @foreach($groups as $group)
                    <tr>
                        <td class="text-center">{{$group->id}}</td>
                        <td>{{$group->name}}</td>
                        <td>{{$group->slug}}</td>
                        <td class="text-center"><a href="{{route('groups.edit', ['group' => $group->id])}}" class="btn btn-success btn-sm">Изменить</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('tmp_js')

@endsection

