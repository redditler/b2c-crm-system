@extends('adminlte::page')

@section('content_header')
    <div class="title-page">
        <h1 class="title-page__name">Создать групу</h1>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <form action="{{route('groups.store')}}" method="post">
                {{csrf_field()}}
                Name
                <input type="text" name="name">
                Slug
                <input type="text" name="slug">
                <input type="submit" value="Создать">
            </form>
        </div>
    </div>
@endsection

@section('tmp_js')

@endsection

