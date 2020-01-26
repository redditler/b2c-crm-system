@extends('adminlte::page')

@section('title', 'Steko')

@section('content')
    {{--<div class="container show-contacts__header">--}}
    {{--<div class="row">--}}
    {{--<a href="/contacts">Назад</a>--}}
    {{--</div>--}}
    {{--<div class="row">--}}
    {{--Контакты / --}}
    {{--</div>--}}
    {{--</div>--}}
    <div class="contacts-max-w">
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1 create_contacts">
                    <div class="panel-heading">
                        <div class="row">
                            <a href="/contacts" id="create_contacts-back">
                                <i class="fa fa-fw fa-chevron-left" aria-hidden="true"></i>
                                <span class="">Назад</span>
                            </a>
                        </div>
                        <div class="row" id="create_contacts-add">
                            Контакты / Добавить контакт
                        </div>
                    </div>
                    <div class="panel-body" id="create_contacts">

                        @include('partials.form-status')

                        {!! Form::open(array('action' => 'ContactsController@store', 'method' => 'POST', 'role' => 'form')) !!}
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="form-group has-feedback col-xs-5">
                                {!! Form::label('fio', 'ФИО', array('class' => 'control-label')); !!}
                                <div class="input-group col-xs-7">
                                    {!! Form::text('fio', NULL, array('id' => 'fio', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group has-feedback col-xs-5">
                                {!! Form::label('region_id', 'Область' , array('class' => 'control-label')); !!}

                                <div class="input-group col-xs-7">
                                    {!! Form::select('region_id', $regions, null, array('id' => 'region_id', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group has-feedback col-xs-5">
                                {!! Form::label('city', 'Город (пгт)', array('class' => 'control-label')); !!}
                                <div class="input-group col-xs-7 ">
                                    {!! Form::text('city', NULL, array('id' => 'city', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="phones col-xs-5">
                                <input type="text" id="phones" value="1" hidden>
                                <div class="form-group has-feedback">
                                    <div class="row">
                                        <div class="col-xs-7">
                                            {!! Form::label('phone', 'Телефон', array('class' => 'control-label')); !!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-group" id="create-contacts__phone">
                                            <div class="col-xs-5">
                                                {!! Form::text('phone', NULL, array('name' => 'phone', 'class' => 'form-control')) !!}
                                            </div>
                                            <div class="col-xs-2 no-padding">
                                                <button type="button" id="add-phone" class="btn">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(!empty(old('phones')))
                                    @foreach(old('phones') AS $key=>$phone)
                                        <div class="form-group has-feedback">
                                            <label for="phones" class="control-label">Телефон</label>
                                            <div>
                                                <div class="input-group">
                                                    <input class="form-control" name="phones[]" type="text"
                                                           value="{{$phone}}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group has-feedback col-xs-5">
                                {!! Form::label('email', 'E-mail', array('class' => 'control-label')); !!}
                                <div class="input-group col-xs-7">
                                    {!! Form::text('email', NULL, array('id' => 'email', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group has-feedback сol-xs-2" id="create_contacts-dealer">
                                {{ Form::checkbox('diler', 1, null, ['class' => 'field', 'name' => 'diler', 'id' => "checkbox2"]) }}
                                {!! Form::label('checkbox2', 'Дилер Steko' , array('class' => 'control-label')); !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group has-feedback col-xs-5">
                                {!! Form::label('user_id', 'Ответственный менеджер' , array('class' => 'control-label')); !!}
                                <div class="input-group col-xs-7">
                                    {!! Form::select('user_id', $users, null, array('id' => 'user_id', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group has-feedback col-xs-5">
                                {!! Form::label('group_id', 'Подразделение' , array('class' => 'control-label')); !!}
                                <div class="input-group col-xs-7">
                                    {!! Form::select('group_id', $groups, null, array('id' => 'group_id', 'class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>

                        {!! Form::button('<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;<span>Добавить контакт</span>', array('class' => 'btn btn-success btn-flat margin-bottom-1 col-xs-2 col-xs-offset-5','type' => 'submit', 'id' => 'create_contact_add-brn' )) !!}

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@section('tmp_js')
    <script type="text/javascript">

        $('#add-phone').click(function () {
            // var phone_id = parseInt($('#phones').val()) + 1;
            // $('#phones').val(phone_id);
            $('.phones').append('<div class="form-group has-feedback">' +
                '    <div class="input-group col-xs-7">' +
                '      <input class="form-control" name="phones[]" type="text">' +
                '    </div>' +
                '</div>')
        });
    </script>
@endsection