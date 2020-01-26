@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <h1 class="content_header__h1">Точки продаж</h1>
@stop

@section('content')
    <div class="container-fluid salons__">

        <div class="row">
            <div class="col-md-2" style="padding: 5px 0">
                <a href="{{route('salons.create')}}" class="btn btn-success glyphicon glyphicon-plus"> Добавить
                    точку продаж</a>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="group">
                    @foreach($groups as $group)
                        <option value="{{$group->id}}">{{$group->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 text-center table-bordered bg-aqua-active" style="
    padding-left: 0px;
    padding-right: 0px;
">
                <div class="col-md-1">#</div>
                <div class="col-md-2">
                    <h4>Название точки продаж</h4>
                </div>
                <div class="col-md-1">
                    <h4>Группа</h4>
                </div>
                <div class="col-md-1">
                    <h4>Регион</h4>
                </div>
                <div class="col-md-1">
                    <h4>Телефон</h4>
                </div>
                <div class="col-md-2">
                    <h4>Адрес</h4>
                </div>
                <div class="col-md-1">
                    <h4>Дата открытия</h4>
                </div>
                <div class="col-md-1">
                    <h4>Действия</h4>
                </div>
            </div>
        </div>

        @foreach($branches as $branch)

            <div class="row" style="padding: 2px 0;font-size: 1.2em">
                <div class="col-md-1 numderSalon">

                </div>
                <div class="col-md-2">
                    {{$branch->name}}
                </div>
                <div class="col-md-1">
                    {{$branch->groups->name ?? 'Не установлен'}}
                </div>
                <div class="col-md-1 text-center">
                    {{$regions->where('id', $branch->region_id)->first()->name}}
                </div>
                <div class="col-md-1">
                    {{$branch->phone ? $branch->phone : 'не указан'}}
                </div>
                <div class="col-md-2">
                    {{$branch->address ? $branch->address : 'не указан'}}
                </div>
                <div class="col-md-1 text-center">
                    {{--{{dd(\Carbon\Carbon::make($branch->date_opening)->format('d-m-Y'))}}--}}
                    {{$branch->date_opening ? \Carbon\Carbon::make($branch->date_opening)->format('d-m-Y') : 'Дата отсутствует'}}
                </div>
                <div class="col-md-1">
                    <a href="{{route('salons.edit', $branch->id)}}"
                       class="btn btn-facebook glyphicon glyphicon-pencil"></a>
                </div>
                <div class="col-md-1">
                    <form action="{{route('salons.destroy', ['salon' => $branch->id])}}" method="post">
                        {{csrf_field()}}
                        {{method_field('DELETE')}}
                        <button class="btn btn-danger glyphicon glyphicon-trash"></button>
                    </form>
                </div>
                <div class="col-md-1">
                    <form action="{{route('setStatus')}}" method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="id" value="{{$branch->id}}">
                        <button class="btn btn-info glyphicon {{$branch->active ? 'glyphicon-ok' : 'glyphicon-remove'}}"></button>
                    </form>

                </div>
            </div>
        @endforeach
    </div>
@endsection
@section('users_js')
    <script>
        $(document).ready(function () {
            let nuberSalon = $('.numderSalon');
            $.each(nuberSalon, function (i, item) {
                $(this).text(i + 1);
            });


        })
    </script>
@endsection