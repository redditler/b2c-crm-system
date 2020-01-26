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
            <div class="col-md-8">
                <div class="card">
                    <div class="panel-heading">Секретный ключ двухфакторной аутентификации</div>
                    <div class="card-body">
                        <span style="color: red;">Внимание! Этот код отображается только один раз. Не закрывайте и не обновляйте вкладку, пока не отсканируете код.</span>
                        <br/><br/>
                        Откройте приложение Authenticator на Вашем смартфоне и отсканируйте QR-код:
                        <br/>
                        <br/>
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(!($data['user']->passwordSecurity)))
                            <form class="form-horizontal" method="POST" action="{{ route('generate2faSecret') }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Генерация секретного ключа для включения 2FA
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @elseif(!$data['user']->passwordSecurity->google2fa_enable)
                            <strong>1. Сканируйте этот штрих-код с помощью приложения Google Authenticator:</strong><br/>
                            <img src="{{$data['google2fa_url'] }}" alt="">
                            <br/><br/>
                            Если Ваш телефон не поддерживает QR-коды,
                            введите следующий код:
                            <h3>{{ $data['google2fa_secret'] }}</h3>
                            <br/><br/>
                            <strong>2.Введите пин-код, чтобы включить 2FA</strong><br/><br/>
                            <form class="form-horizontal" method="POST" action="{{ route('enable2fa') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('verify-code') ? ' has-error' : '' }}">
                                    <label for="verify-code" class="col-md-4 control-label">Код аутентификатора</label>

                                    <div class="col-md-6">
                                        <input id="verify-code" type="password" class="form-control" name="verify-code"
                                               required>

                                        @if ($errors->has('verify-code'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('verify-code') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Включить 2FA
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @elseif($data['user']->passwordSecurity->google2fa_enable)
                            <script type="text/javascript">
                                window.location.href = "/leeds"
                            </script>
                            <div class="alert alert-success">
                                2FA В настоящее время <strong> включено </strong> для вашего аккаунта.
                            </div>
                            <p>Если вы хотите отключить двухфакторную аутентификацию. Пожалуйста,
                                подтвердите ваш пароль и нажмите кнопку Отключить 2FA кнопку.</p>
                            <form class="form-horizontal" method="POST" action="{{ route('disable2fa') }}">
                                <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                    <label for="change-password" class="col-md-4 control-label">Ваш пароль</label>

                                    <div class="col-md-6">
                                        <input id="current-password" type="password" class="form-control"
                                               name="current-password" required>

                                        @if ($errors->has('current-password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('current-password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-md-offset-5">

                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-primary ">Отключить 2FA</button>
                                </div>
                            </form>
                        @endif
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