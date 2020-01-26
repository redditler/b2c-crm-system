@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <h1>404</h1>
@stop

@section('content')
    @if(!isset($msg))
        Not found!
    @else
        {{$msg}}
    @endif
@stop


@section('tmp_js')

@endsection