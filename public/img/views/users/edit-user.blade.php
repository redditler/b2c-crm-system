@extends('adminlte::page')

@section('template_title')
    Editing User {{ $user->name }}
@endsection

@section('users_css')
    <style type="text/css">
        .pw-change-container {
            display: none;
        }
    </style>
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">

                        <strong>Editing User:</strong> {{ $user->name }}

                        <a href="/users/{{$user->id}}" class="btn btn-primary btn-xs pull-right"
                           style="margin-left: 1em;">
                            <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                            Back <span class="hidden-xs">to User</span>
                        </a>

                        <a href="/users" class="btn btn-info btn-xs pull-right">
                            <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                            <span class="hidden-xs">Back to </span>Users
                        </a>

                    </div>

                    {!! Form::model($user, array('action' => array('UsersController@update', $user->id), 'method' => 'PUT')) !!}

                    {!! csrf_field() !!}

                    <div class="panel-body">

                        @include('partials.form-status')

                        <div class="form-group has-feedback row">
                            {!! Form::label('name', 'Name' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('name', old('name'), array('id' => 'name', 'class' => 'form-control', 'placeholder' => Lang::get('forms.ph-username'))) !!}
                                    <label class="input-group-addon" for="name"><i class="fa fa-fw fa-user }}"
                                                                                   aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            {!! Form::label('email', 'E-mail' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('email', old('email'), array('id' => 'email', 'class' => 'form-control', 'placeholder' => Lang::get('forms.ph-useremail'))) !!}
                                    <label class="input-group-addon" for="email"><i class="fa fa-fw fa-envelope "
                                                                                    aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-feedback row">
                            {!! Form::label('telegram_id', 'Telegram ID' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::text('telegram_id', old('telegram_id'), array('id' => 'telegram_id', 'class' => 'form-control', 'placeholder' => 'Telegram ID', 'maxlength'=>'9')) !!}
                                    <label class="input-group-addon" for="email"><i class="fa fa-fw fa-telegram "
                                                                                    aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            {!! Form::label('role_id', 'Должность' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    @if(!Auth::user()->analyst || $user->id == Auth::user()->id)
                                        {!! Form::select('role_id', $user->roles_array, null, array('id' => 'role_id','disabled', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                    @else
                                        {!! Form::select('role_id', $user->roles_array, null, array('id' => 'role_id', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                    @endif
                                    <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                    aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            {!! Form::label('group_id', 'Группа' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    @if(!Auth::user()->analyst || $user->id == Auth::user()->id)
                                        {!! Form::select('group_id', $user->groups_array, null, array('id' => 'group_id','disabled', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                    @else
                                        {!! Form::select('group_id', $user->groups_array, null, array('id' => 'group_id', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                    @endif
                                    <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                    aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback row">
                            {!! Form::label('branch_id', 'Филиал' , array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">

                                    @if(!Auth::user()->analyst || $user->id == Auth::user()->id)
                                        {!! Form::select('branch_id', $branches, $user->branch_id, array('id' => 'group_id','disabled', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                    @else
                                        {!! Form::select('branch_id', $branches, $user->branch_id, array('id' => 'branch_id', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                    @endif


                                    <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                    aria-hidden="true"></i></label>
                                </div>
                            </div>
                        </div>
                        @if(!$user->analyst || !$user->chief)
                            <div class="form-group has-feedback row">
                                {!! Form::label('region_id', 'Регион' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="box-body">
                                        @foreach($regions AS $key=>$region)
                                            <div class="col-md-2">
                                                <div class="checkbox">
                                                    <label>
                                                        @if(in_array($key,$user->regions()->get()->pluck('region_id')->toArray()))
                                                            @if(!Auth::user()->analyst || $user->id == Auth::user()->id)
                                                                {{ Form::checkbox('agree', $key, 1, ['class' => 'field','disabled', 'name' => 'regions[]']) }} {{$region}}
                                                            @else
                                                                {{ Form::checkbox('agree', $key, 1, ['class' => 'field', 'name' => 'regions[]']) }} {{$region}}
                                                            @endif
                                                        @else
                                                            @if(!Auth::user()->analyst || $user->id == Auth::user()->id)
                                                                {{ Form::checkbox('agree', $key, null, ['class' => 'field','disabled', 'name' => 'regions[]']) }} {{$region}}
                                                            @else
                                                                {{ Form::checkbox('agree', $key, null, ['class' => 'field', 'name' => 'regions[]']) }} {{$region}}
                                                            @endif
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="pw-change-container">
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
                        </div>

                    </div>
                    <div class="panel-footer">

                        <div class="row">

                            <div class="col-xs-6">
                                <a href="#" class="btn btn-default btn-block margin-bottom-1 btn-change-pw"
                                   title="Change Password">
                                    <i class="fa fa-fw fa-lock" aria-hidden="true"></i>
                                    <span></span> Change Password
                                </a>
                            </div>

                            <div class="col-xs-6">
                                {!! Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i> Save Changes', array('class' => 'btn btn-success btn-block margin-bottom-1 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => Lang::get('modals.edit_user__modal_text_confirm_title'), 'data-message' => Lang::get('modals.edit_user__modal_text_confirm_message'))) !!}
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

    @include('modals.modal-save')
    @include('modals.modal-delete')

@endsection

@section('users_js')

    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')

    <script type="text/javascript">
        $('.btn-change-pw').click(function (event) {
            event.preventDefault();
            $('.pw-change-container').slideToggle(100);
            $(this).find('.fa').toggleClass('fa-times');
            $(this).find('.fa').toggleClass('fa-lock');
            // $(this).find('span').toggleText('', 'Cancel');
        });
        // $("input").keyup(function() {
        //   if(!$('input').val()){
        //       $(".btn-save").hide();
        //   }
        //   else {
        //       $(".btn-save").show();
        //   }
        // });

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