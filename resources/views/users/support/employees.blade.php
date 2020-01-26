@extends('adminlte::page')

@section('content_header')
    <div class="title-page">
        <h1 class="title-page__name">Сотрудники</h1>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-4">
            <p><strong>Количество работающих сотрудников: {{$employees->count()}}</strong></p>
        </div>
    </div>
   <table class="table table-bordered table-striped bg-gray">
       <tr class="text-center">
           <td><strong>Ф.И.О.</strong></td>
           <td><strong>Должность</strong></td>
           {{--<td>{{$employee->group->name}}</td>--}}
           <td><strong>Салон</strong></td>
       </tr>
        @foreach($employees as $employee)
            <tr>
                <td>{{$employee->name}}</td>
                <td>{{$employee->role->name}}</td>
                {{--<td>{{$employee->group->name}}</td>--}}
                <td>{{$employee->branch->name}}</td>
            </tr>
        @endforeach
   </table>
@endsection

@section('tmp_js')

@endsection

