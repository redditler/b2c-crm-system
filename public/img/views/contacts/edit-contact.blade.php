@extends('adminlte::page')

@section('title', 'Steko')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">

                        Контакты / Изменить контакт контакт

                        <a href="/contacts" class="btn btn-info btn-xs pull-right">
                            <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                            <span class="hidden-sm hidden-xs">Назад</span>
                        </a>

                    </div>
                    <div class="panel-body">

                        @include('partials.form-status')

                        {!! Form::open(array('action' => array('ContactsController@update',$contact->id), 'method' => 'PUT')) !!}

                        {!! csrf_field() !!}

                        <div class="form-group has-feedback row">
                            {!! Form::label('fio', 'ФИО', array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('fio', $contact->fio, array('id' => 'fio', 'class' => 'form-control')) !!}
                                    <label class="input-group-addon" for="name"><i
                                                class="fa fa-fw {{ Lang::get('forms.create_user_icon_username') }}"
                                                aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            {!! Form::label('region_id', 'Область' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::select('region_id', $regions, $contact->region_id, array('id' => 'region_id', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                    <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                    aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            {!! Form::label('city', 'Город (пгт)', array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('city', $contact->city, array('id' => 'city', 'class' => 'form-control')) !!}
                                    <label class="input-group-addon" for="name"><i
                                                class="fa fa-fw {{ Lang::get('forms.create_user_icon_username') }}"
                                                aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            {!! Form::label('email', 'E-mail', array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('email', $contact->email, array('id' => 'email', 'class' => 'form-control')) !!}
                                    <label class="input-group-addon" for="email"><i
                                                class="fa fa-fw {{ Lang::get('forms.create_user_icon_email') }}"
                                                aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group has-feedback row">
                            {!! Form::label('user_id', 'Ответственный менеджер' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::select('user_id', $users, $contact->user_id, array('id' => 'user_id', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                    <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                    aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            {!! Form::label('group_id', 'Подразделение' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::select('group_id', $groups, $contact->group_id, array('id' => 'group_id', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                    <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                    aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            {!! Form::label('diler', 'Дилер Steko' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                {{ Form::checkbox('diler', 1, $contact->diler, ['class' => 'field', 'name' => 'diler']) }}
                            </div>
                        </div>
                        <div class="phones">
                            <input type="text" id="phones" value="1" hidden>
                            <div class="form-group has-feedback row">
                                {!! Form::label('phone', 'Телефон', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        @foreach($contact->phones AS $key=>$phone)
                                            @if($key == 0)
                                                {!! Form::text('phone', $phone->phone, array('name' => 'phone', 'class' => 'form-control')) !!}
                                            @endif
                                        @endforeach
                                            <label class="input-group-addon" for="name"><i
                                                        class="fa fa-fw {{ Lang::get('forms.create_user_icon_username') }}"
                                                        aria-hidden="true"></i></label>

                                    </div>
                                </div>
                            </div>
                            @foreach($contact->phones AS $key=>$phone)
                                @if($key != 0)
                                    <div class="form-group has-feedback row">
                                        <label for="phones" class="col-md-3 control-label">Телефон</label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <input class="form-control" name="phones[]" type="text" value="{{$phone->phone}}">
                                                <label class="input-group-addon" for="name"><i class="fa fa-fw fa-user" aria-hidden="true"></i></label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="form-group has-feedback row">
                            <div class="col-md-2">
                                <button type="button" id="add-phone" class="btn btn-block btn-default">Добавить</button>
                            </div>
                        </div>

                        {!! Form::button('Изменить контакт', array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right','type' => 'submit', )) !!}

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
            $('.phones').append('<div class="form-group has-feedback row">' +
                '  <label for="phones" class="col-md-3 control-label">Телефон</label>' +
                '  <div class="col-md-9">' +
                '    <div class="input-group">' +
                '      <input class="form-control" name="phones[]" type="text">' +
                '          <label class="input-group-addon" for="name"><i class="fa fa-fw fa-user" aria-hidden="true"></i></label>' +
                '    </div>' +
                '  </div>' +
                '</div>')
        });
    </script>
@endsection