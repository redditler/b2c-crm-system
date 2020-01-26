@extends('adminlte::page')

@section('title', 'Steko')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.css">

@section('content_header')
    <h1>Журнал</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <span>Номер машины - {{$car->car_number}}</span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <span>Группа - {{$car->groupe->groupe_name}}</span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <span>Код группа - {{$car->groupe->code}}</span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <span>Поставщик - {{$car->provider->provider_name}}</span>
        </div>
    </div>

    <div class="row" style="height: 50px;">
        <div class="col-md-3" style="margin-top: 10px;">
            <span>{{$security->direction->direction_name}}</span> - <span>{{$security->name}}</span>
        </div>
        <div class="col-md-4">
            <i class="fa fa-check" aria-hidden="true" style="color: green;margin-top: 10px;"></i>
            <span>Впустил</span>
            &nbsp;&nbsp;&nbsp;<span>{{$action_security->created_at}}</span>
            &nbsp;&nbsp;&nbsp;<span>{{$action_security->user_comment}}</span>
        </div>
    </div>

    @foreach($users as $user)
        <div class="row" style="margin-top: 20px;">
        {{--{{var_dump($user)}}--}}

            <div class="col-md-3">
                <span>{{$user['direction']}}</span> - <span>{{$user['name']}}</span>
            </div>

        @if ($user['action'] == NULL && $user['btns'])

                <div class="col-md-9">
                    @if($user['direction_id'] != 3)
                        {!! Form::open(array('action' => 'HomeController@carAccept', 'method' => 'POST', 'role' => 'form')) !!}

                        {!! csrf_field() !!}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-9">
                                    {!! Form::label('comment', 'Комментарий', array('class' => 'control-label')); !!}
                                    {!! Form::text('comment', NULL, array('id' => 'comment', 'class' => 'form-control')) !!}
                                    {!! Form::text('id', $car->id, array('id' => '', 'hidden')) !!}
                                    {!! Form::text('action', '5', array('id' => '', 'hidden')) !!}
                                </div>
                                <div class="col-md-3">
                                    {!! Form::button('Ознакомлен(а)', array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right','type' => 'submit', 'style' => 'margin-top: 24px;padding: 6px;')) !!}
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>


                    @else
                        {!! Form::open(array('action' => 'HomeController@carAccept', 'method' => 'POST', 'role' => 'form')) !!}

                        {!! csrf_field() !!}
                        <div class="row" id="problems">
                        @foreach($user['problems'] AS $problem)
                            <div class="col-md-2">
                                <div class="checkbox">
                                    <label>
                                        {{ Form::checkbox('agree', $problem->id, null, ['class' => 'field']) }} {{$problem->problem_name}}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                            {!! Form::text('problem_id', null, array('id' => 'problem_id', 'hidden')) !!}
                            {!! Form::text('id', $car->id, array('id' => '', 'hidden')) !!}
                            {!! Form::text('action', '1', array('id' => '', 'hidden')) !!}
                        </div>

                        <div class="row">
                            <div class="col-md-5">

                                <div class="col-md-9">
                                    {!! Form::label('comment', 'Комментарий', array('class' => 'control-label')); !!}
                                    {!! Form::text('comment', NULL, array('id' => 'comment', 'class' => 'form-control')) !!}
                                </div>
                                <div class="col-md-3">
                                    {!! Form::button('<i class="fa fa-truck" aria-hidden="true"></i>&nbsp;Принял', array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right','type' => 'submit', 'style' => 'margin-top: 24px;padding: 6px;')) !!}
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>

                    @endif
                </div>

        @else
                <div class="col-md-5">
                    @if ($user['action']['action'] == 1 || $user['action']['action'] == 5)
                        <i class="fa fa-check" aria-hidden="true" style="color: green;margin-top: 10px;"></i>
                        &nbsp;&nbsp;&nbsp;<span>{{$user['action']['created_at']}}</span>
                        &nbsp;&nbsp;&nbsp;<span>{{$user['action']['user_comment']}}</span>
                        @if($user['direction_id'] == 3)
                            <div class="row" id="problems">
                                @foreach($user['problems'] AS $problem)
                                    <div class="col-md-5">
                                        <div class="checkbox">
                                            <label>
                                                @if(strpos($user['action']['problem_id'], (string)$problem->id) !== false)
                                                    {{ Form::checkbox('agree', $problem->id, 1, ['class' => 'field', 'disabled']) }} {{$problem->problem_name}}
                                                @else
                                                    {{ Form::checkbox('agree', $problem->id, null, ['class' => 'field', 'disabled']) }} {{$problem->problem_name}}
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif

                    @if($user['action']['action'] === NULL)
                        <i class="fa fa-hourglass-half"></i>
                    @endif

                </div>
        @endif
        </div>
    @endforeach

    @if ($car->status == 1 || $car->status == 2)
        @if(empty($security_close) && Auth::user()->security)
        <div class="row" style="height: 85px;">
            <div class="col-md-3" style="margin-top: 30px;">
                <span>{{$security_current_user->direction->direction_name}}</span> - <span>{{$security_current_user->name}}</span>
            </div>

        <div class="col-md-4">
            {!! Form::open(array('action' => 'HomeController@carAccept', 'method' => 'POST', 'role' => 'form')) !!}

            {!! csrf_field() !!}
            <div class="col-md-9">
                {!! Form::label('comment', 'Комментарий', array('class' => 'control-label')); !!}
                {!! Form::text('comment', NULL, array('id' => 'comment', 'class' => 'form-control')) !!}
                {!! Form::text('id', $car->id, array('id' => '', 'hidden')) !!}
                {!! Form::text('action', '4', array('id' => '', 'hidden')) !!}
            </div>
            <div class="col-md-3">
                @if($car->status == 1)
                    {!! Form::button('<i class="fa fa-truck" aria-hidden="true"></i>&nbsp;Выпустить', array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right disabled','disabled','type' => 'submit', 'style' => 'margin-top: 24px;padding: 6px;')) !!}
                @else
                    {!! Form::button('<i class="fa fa-truck" aria-hidden="true"></i>&nbsp;Выпустить', array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right','type' => 'submit', 'style' => 'margin-top: 24px;padding: 6px;')) !!}
                @endif
            </div>
            {!! Form::close() !!}
        </div>
        </div>
        @endif
    @else
        @if(!empty($security_close))
        <div class="row" style="height: 50px;">
            <div class="col-md-3" style="margin-top: 10px;">
                <span>{{$security_close->direction->direction_name}}</span> - <span>{{$security_close->name}}</span>
            </div>
            <div class="col-md-4">
                <i class="fa fa-check" aria-hidden="true" style="color: green;margin-top: 10px;"></i>
                <span>Выпустил</span>
                &nbsp;&nbsp;&nbsp;<span>{{$action_security_close->created_at}}</span>
                &nbsp;&nbsp;&nbsp;<span>{{$action_security_close->user_comment}}</span>
            </div>
        </div>
        @endif
    @endif
    @if(($car->status == 1 || $car->status == 2) && Auth::user()->security)
    <div class="row">
        <div class="col-md-3">
            <a href="/edit/{{$car->id}}" class="btn btn-info btn-flat margin-bottom-1">Редактировать</a>
            <a href="/del/{{$car->id}}" class="btn btn-danger btn-flat margin-bottom-1">Удалить</a>
        </div>
    </div>
    @endif

@section('users_js')
<script>
    $('input[name=agree]').change(function() {
        var problem_id = '';
        $('#problems input[type=checkbox]').each(function() {
            if(this.checked){
                problem_id += $(this).val() + ',';
            }
        });
        problem_id = problem_id.substring(0, problem_id.length - 1)

        $('#problem_id').val(problem_id);
    });
</script>
@endsection
@stop