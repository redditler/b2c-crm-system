@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <h1 class="header-stat">Региональные мененджеров</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{--<div class="col-md-2" style="padding: 5px 0">--}}
            {{--<a href="{{route('rm.create')}}" class="btn btn-success glyphicon glyphicon-plus"> Добавить регионального мененджера</a>--}}
            {{--</div>--}}
            <div class="col-md-12 text-center table-bordered bg-aqua-active">
                <div class="col-md-1">#</div>
                <div class="col-md-2">
                    <h4 class="name">Имя мененджера</h4>
                </div>
                <div class="col-md-2 text-center">
                    <h4>Группа</h4>
                </div>
                <div class="col-md-5">
                    <h4>Точки продаж</h4>
                </div>
                <div class="col-md-2">
                    <h4>Действия</h4>
                </div>
            </div>
        </div>
        @foreach($rms as $rm)

            <div class="row rm bg-info">
                <div class="col-md-1 text-center"><h4 class="serialNumber"></h4></div>
                <div class="col-md-2"><h4>{{$rm['name']}}</h4></div>
                <div class="col-md-2 text-center"><h4>{{$rm['group']['name']}}</h4></div>
                <div class="col-md-5">
                    <h4>
                        @foreach($checked as $val)
                            @if($rm['id'] == $val['user_id'])
                                @foreach($salon as $value)
                                    @if($val['user_branch_id'] == $value['id'])
                                        <span class="bg bg-info" >
                                            {{$value['name']}}/
                                        </span>

                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </h4>
                </div>
                <div class="col-md-2 text-center">
                    <a href="{{route('rm.edit', ['rm' => $rm['id']])}}" class="btn bg-blue-gradient">Изменить</a>
                </div>

            </div>
        @endforeach
    </div>

    </div>
@endsection
@section('tmp_js')
    <script>
        $(document).ready(function () {
            let serialNumber = $('.serialNumber');
            for (let i = 0; i < serialNumber.length; i++){
                serialNumber[i].textContent = 1 + i;
            }
        });
    </script>
@endsection