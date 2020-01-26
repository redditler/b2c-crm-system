@extends('adminlte::master')

@section('body')
    <section class="login-page__wrapper" data-id="login-page">

        <div class="brand__logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo__wrapper">
                <img src="/img/sidebar/logo.svg" alt="logo" class="logo">
            </a>
        </div>

        <div class="login-page__content">
            <div class="content__bg"></div>
            <div class="content__wrapper">
                <div class="content__title">
                    <h3 class="title">Вход в систему</h3>
                </div>
                <form  action="{{ url(config('adminlte.login_url', 'login')) }}" method="post" class="content__form">
                    {!! csrf_field() !!}

                    <label for="username" class="form__user-name  {{ $errors->has('username') ? 'has-error' : '' }}">
                        <input type="text" class="form__input" name="username" value="{{ old('username') }}" placeholder="Логин">
                    </label>

                    <label for="password" class="form__password  {{ $errors->has('username') ? 'has-error' : '' }}">
                        <input type="password" class="form__input" name="password" placeholder="Пароль">
                    </label>

                    <label for="remember" class="form__remember">
                        <input type="checkbox" name="remember"  class="form__checkbox">
                        <span class="form__checkbox-title">Запомнить меня</span>
                    </label>
                    <button type="submit" class="form__submit btn btn--default blue">Вход</button>
                </form>

            </div>
        </div>
    </section>
@stop

@section('adminlte_js')
    @yield('js')
@stop
