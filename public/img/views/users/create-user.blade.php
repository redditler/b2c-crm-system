@extends('adminlte::page')

@section('template_title')
    Create New User
@endsection

@section('users_css')
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">

                        Create New User

                        <a href="/users" class="btn btn-info btn-xs pull-right">
                            <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                            <span class="hidden-sm hidden-xs">Back to </span><span class="hidden-xs">Users</span>
                        </a>

                    </div>
                    <div class="panel-body">

                        @include('partials.form-status')

                        {!! Form::open(array('action' => 'UsersController@store', 'method' => 'POST', 'role' => 'form')) !!}

                        {!! csrf_field() !!}

                        <div class="form-group has-feedback row">
                            {!! Form::label('email', Lang::get('forms.create_user_label_email'), array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('email', NULL, array('id' => 'email', 'class' => 'form-control', 'placeholder' => Lang::get('forms.create_user_ph_email'))) !!}
                                    <label class="input-group-addon" for="email"><i
                                                class="fa fa-fw {{ Lang::get('forms.create_user_icon_email') }}"
                                                aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            {!! Form::label('name', Lang::get('forms.create_user_label_username'), array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('name', NULL, array('id' => 'name', 'class' => 'form-control', 'placeholder' => Lang::get('forms.create_user_ph_username'))) !!}
                                    <label class="input-group-addon" for="name"><i
                                                class="fa fa-fw {{ Lang::get('forms.create_user_icon_username') }}"
                                                aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            {!! Form::label('telegram_id', 'Telegram ID' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('telegram_id', null, array('id' => 'telegram_id', 'class' => 'form-control', 'placeholder' => 'Telegram ID', 'maxlength'=>'9')) !!}
                                    <label class="input-group-addon" for="email"><i class="fa fa-fw fa-telegram "
                                                                                    aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            {!! Form::label('role_id', 'Должность' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::select('role_id', $roles, null, array('id' => 'role_id', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                    <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                    aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            {!! Form::label('group_id', 'Группа' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::select('group_id', $groups, null, array('id' => 'group_id', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                    <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                    aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            {!! Form::label('branch_id', 'Филиал' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::select('branch_id', $branches, null, array('id' => 'branch_id', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                    <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                    aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            {!! Form::label('region_id', 'Регион' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="box-body">
                                    @foreach($regions AS $key=>$region)
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    {{ Form::checkbox('agree', $key, null, ['class' => 'field', 'name' => 'regions[]']) }} {{$region}}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            {!! Form::label('password', Lang::get('forms.create_user_label_password'), array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::password('password', array('id' => 'password', 'class' => 'form-control ', 'placeholder' => Lang::get('forms.create_user_ph_password'))) !!}
                                    <label class="input-group-addon" for="password"><i
                                                class="fa fa-fw {{ Lang::get('forms.create_user_icon_password') }}"
                                                aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            {!! Form::label('password_confirmation', Lang::get('forms.create_user_label_pw_confirmation'), array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => Lang::get('forms.create_user_ph_pw_confirmation'))) !!}
                                    <label class="input-group-addon" for="password_confirmation"><i
                                                class="fa fa-fw {{ Lang::get('forms.create_user_icon_pw_confirmation') }}"
                                                aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>

                        {!! Form::button('<i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;' . Lang::get('forms.create_user_button_text'), array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right','type' => 'submit', )) !!}

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('users_js')

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')

    <script type="text/javascript">

        $('#direction_id').change(function () {
            if ($(this).val() == 6 || $(this).val() == 7) {
                $('#providers').hide();

                $('#providers :input[type=checkbox]').each(function () {
                    if (this.checked) {
                        $(this).prop('checked', false);
                    }
                });
            }
            else {
                $('#providers').show();
            }
        });

        $('input[name=agree]').change(function () {
            var provider_id = ',';
            $('#providers :input[type=checkbox]').each(function () {
                if (this.checked) {
                    provider_id += $(this).val() + ',';
                }
            });

            $('#provider_id').val(provider_id);
            // console.log(provider_id);
        });
    </script>

@endsection