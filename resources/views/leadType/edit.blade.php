@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <div class="leed-requests">
        <h1 class="content_header__h1">Редактировать типы лидов</h1>
        <a href="{{route('leadType.index')}}" class="btn btn-info">Вернутся</a>
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <form action="{{route('leadType.update', ['leadType' => $leadType['id']])}}" method="post">
                    {{csrf_field()}}
                    {{ method_field('PUT') }}
                    <div class="form-group">
                        <label>Name
                            <input type="text" class="form-control" name="title" value="{{$leadType['title']}}">
                        </label>
                        <label>Slug
                            <input type="text" class="form-control" name="slug" value="{{$leadType['slug']}}">
                        </label>
                    </div>
                    <input type="submit" class="btn btn-success" value="Submit">
                </form>
            </div>
        </div>
    </div>
@endsection