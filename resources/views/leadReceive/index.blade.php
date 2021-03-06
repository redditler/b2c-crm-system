@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <div class="leed-requests">
        <h1 class="content_header__h1">Типы полученых лидов</h1>
        <a href="{{route('leadReceive.create')}}" class="btn btn-success">Добавить</a>
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr class="text-center">
                        <td>ID</td>
                        <td>Title</td>
                        <td>Slug</td>
                        <td>Action</td>
                    </tr>
                    @foreach($leadReceive as $val)
                        <tr>
                            <td>{{$val->id}}</td>
                            <td>{{$val->title}}</td>
                            <td>{{$val->slug}}</td>
                            <td><a href="{{route('leadReceive.edit', ['leadReceive' => $val->id])}}" class="btn btn-success">Edit</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

@endsection