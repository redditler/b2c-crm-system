@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <h1 class="header-stat">Региональны менеджер {{$user['name']}}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <a href="{{route('rm.index')}}" class="btn btn-info">Вернутся</a>
                <form action="{{route('rm.update', ['rm' => $user['id']])}}" method="post">
                    {{csrf_field()}}
                    {{method_field('PUT')}}
                    <h3 class="text-black">Выберите точку продаж(ы)</h3>
                    @foreach($branches as $branch)
                        <div class="checkbox">
                            <input type="checkbox" name="{{$branch['id']}}" {{isset($checked[$branch['id']]) ? 'checked' : ''}}  value="{{$branch['name']}}">
                            <h4>{{$branch['name']}} - {{$branch['groups']['name']}}</h4>
                        </div>
                    @endforeach
                    <input class="btn btn-success" type="submit" value="Выбрать">
                </form>
            </div>
        </div>
    </div>
@endsection
@section('users_js')

@endsection