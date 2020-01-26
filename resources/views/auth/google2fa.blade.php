@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-8 ">
                <div class="card">
                    <div class="card-header">Двух факторная авторизация</div>
                    <div class="card-body">
                        {{--<p>Two factor authentication (2FA) strengthens access security by requiring two methods--}}
                            {{--(also referred to as factors) to verify your identity. Two factor authentication protects--}}
                            {{--against phishing, social engineering and password brute force attacks and secures your logins--}}
                            {{--from attackers exploiting weak or stolen credentials.</p>--}}

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <strong>Введите временный пароль из приложения Google Authenticator</strong><br/><br/>
                        <form class="form-horizontal" action="/2faVerify" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('one_time_password-code') ? ' has-error' : '' }}">
                                <label for="one_time_password" class="col-md-4 control-label">Одноразовый пароль</label>
                                <div class="col-md-6">
                                    <input id="one_time_password" name="one_time_password" class="form-control"  type="password" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button class="btn btn-primary" type="submit">Авторизоваться</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
    @yield('js')
@stop