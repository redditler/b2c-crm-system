@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <div class="leed-requests">
        <h1 class="content_header__h1">Статусы лидов</h1>
        <a href="{{route('leadStatus.create')}}" class="btn btn-success">Добавить</a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-7">
            <table class="table table-bordered table-condensed text-center">
                <tr>
                    <td><b>ID</b></td>
                    <td><b>Slug</b></td>
                    <td><b>Name</b></td>
                    <td><b>Action</b></td>
                </tr>
                @foreach($leadStatuses as $leadStatus)
                    <tr>
                        <td>{{$leadStatus['id']}}</td>
                        <td>{{$leadStatus['slug']}}</td>
                        <td>{{$leadStatus['name']}}</td>
                        <td><a href="{{route('leadStatus.edit', ['leadStatus' => $leadStatus['id']])}}" class="btn btn-info">Edit</a></td>
                    </tr>
                @endforeach


            </table>
        </div>
    </div>
@endsection