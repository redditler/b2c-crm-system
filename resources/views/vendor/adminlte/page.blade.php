@php
    use Illuminate\Support\Facades\Auth;

    if (Auth::user()->group_id == 0 ){
        exit('Ожидайте распределенния!');
    }
@endphp
@extends('adminlte::master')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('body')
    <div class="wrapper">

        <!-- Main sidebar -->
        <nav class="sidebar">
            <div class="sidebar__header">
                <div class="sidebar__version">
                    <span class="version--value">Версия 2.17</span>
                </div>
            </div>
            <div class="sidebar__body">
                <div class="sidebar__menu">

                    <!-- Живая лента -->
                    <div class="sidebar__item" id="living_tape">
                        <a href="{{route('leads')}}" class="sidebar__link">
                            <span class="link--title">Живая лента</span>
                            @if(\Illuminate\Support\Facades\Auth::user()->group_id != 4)
                            <div class="link__badge">
                                <span class="badge--vlaue">{{\App\Leed::getLeadAll()}}</span>
                            </div>
                            @endif
                        </a>
                    </div>

                    <!-- Забракованные -->
                    <div class="sidebar__item" id="rejected">
                        <a href="{{route('leadCanceledShow')}}" class="sidebar__link" >
                            <span class="link--title">Забракованные</span>
                            @if(\Illuminate\Support\Facades\Auth::user()->group_id != 4)
                            <div class="link__badge">
                                <span class="badge--vlaue">1856</span>
                            </div>
                            @endif
                        </a>
                    </div>

                    <!-- Акции -->
                    @if(\Illuminate\Support\Facades\Auth::user()->role_id != 5)
                    <div class="sidebar__item" id="stocks">
                        <a href="{{route('leadsPromo')}}" class="sidebar__link">
                            <span class="link--title">Акции</span>
                        </a>
                    </div>
                    @endif

                    <!-- Клиенты -->
                    <div class="sidebar__item" id="customers">
                        <a href="{{route('contact.index')}}" class="sidebar__link">
                            <span class="link--title">Клиенты</span>
                        </a>
                    </div>

                     @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')

                     @if(\Illuminate\Support\Facades\Auth::user()->manager)

                    <!-- Ежедневный отчет -->
                    {{-- <div class="sidebar__item" id="daily_report">
                        <a href="{{route('daily-reports.index')}}" class="sidebar__link">
                            <span class="link--title">Ежедневный отчет</span>
                        </a>
                    </div> --}}

                    <!-- Статистика -->
                    <div class="sidebar__item" id="statistics">
                        <a href="{{route('statisticsNew')}}" class="sidebar__link">
                            <span class="link--title">Статистика</span>
                        </a>
                    </div>

                    @elseif(\Illuminate\Support\Facades\Auth::user()->role_id == 1 || \Illuminate\Support\Facades\Auth::user()->role_id == 2 || \Illuminate\Support\Facades\Auth::user()->role_id == 4)
                    
                    <!-- Статистика -->
                    <div class="sidebar__item" id="statistics">
                        <a href="{{route('statisticsChief')}}" class="sidebar__link">
                            <span class="link--title">Статистика</span>
                        </a>
                    </div>
                    @endif

                    <!-- Телефония -->
                     @if(\Illuminate\Support\Facades\Auth::user()->group_id != 4 && \Illuminate\Support\Facades\Auth::user()->role_id != 3 && \Illuminate\Support\Facades\Auth::user()->role_id != 4)
                     <div class="sidebar__item treeview" id="telephony">
                        <a class="sidebar__link">
                            <span class="link--title">Телефония</span>
                            <span class="dropdown--icon fa fa-chevron-left"></span>
                        </a>
                        <div class="dropdown__menu">
                            <div class="dropdown__item">
                                <a href="{{route('phoneInfo')}}" class="dropdown__link">
                                    <span class="dropdown__link">Статистика</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                     <!-- Сценарии разговора с клиентом -->
                     <div class="sidebar__item" id="callscript">
                        <a href="{{route('callscriptsBegin')}}" class="sidebar__link">
                            <span class="link--title">Сценарии разговора с клиентом</span>
                        </a>
                    </div>

                    @if(\Illuminate\Support\Facades\Auth::user()->role_id <= 2)
                    <!-- План на месяц -->
                    <div class="sidebar__item" id="mountly-plan">
                        <a href="{{route('monthly-plan.index')}}" class="sidebar__link">
                            <span class="link--title">План на месяц</span>
                        </a>
                    </div>

                     @if(\Illuminate\Support\Facades\Auth::user()->group_id != 4)
                    <!-- Отчеты по дням -->
                    <div class="sidebar__item" id="daily_report">
                        <a href="{{route('managerReports.index')}}" class="sidebar__link">
                            <span class="link--title">Отчеты по дням</span>
                        </a>
                    </div>
                    @endif
                    @endif

                    <!-- Органайзер -->
                    <div class="sidebar__item" id="organizer">
                        <a href="{{route('events')}}" class="sidebar__link">
                            <span class="link--title">Органайзер</span>
                        </a>
                    </div>

                    <!-- Видео-курсы -->
                    <div class="sidebar__item" id="video_courses">
                        <a href="{{route('videocourses.index')}}" class="sidebar__link">
                            <span class="link--title">Видео-курсы</span>
                        </a>
                    </div>

                     @if(\Illuminate\Support\Facades\Auth::user()->role_id == 1)
                    <!-- Органайзер -->
                    <div class="sidebar__item" id="dashboards">
                        <a href="{{route('Dashboards.index')}}" class="sidebar__link">
                            <span class="link--title">Дашборды</span>
                        </a>
                    </div>


                    <!-- Настройки -->
                    <div class="sidebar__item treeview" id="setting">
                        <a class="sidebar__link">
                            <span class="link--title">Настройки</span>
                            <span class="dropdown--icon fa fa-chevron-left"></span>
                        </a>
                        <div class="dropdown__menu">
                            <div class="dropdown__item">
                                <a href="{{route('regions.index')}}" class="dropdown__link">
                                    <span class="dropdown__link">Регионы</span>
                                </a>
                            </div>
                            <div class="dropdown__item">
                                <a href="{{route('salons.index')}}" class="dropdown__link">
                                    <span class="dropdown__link">Точки продаж</span>
                                </a>
                            </div>
                            <div class="dropdown__item">
                                <a href="{{route('contactPriceCategory.index')}}" class="dropdown__link">
                                    <span class="dropdown__link">Ценовая категория клиентов</span>
                                </a>
                            </div>
                            <div class="dropdown__item">
                                <a href="{{route('phoneInfo')}}" class="dropdown__link">
                                    <span class="dropdown__link">Квалификация клиента</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->role_id == 2 || \Illuminate\Support\Facades\Auth::user()->role_id == 4)
                    <!-- Сотрудники -->
                    <div class="sidebar__item" id="employees">
                        <a href="{{route('employees')}}" class="sidebar__link">
                            <span class="link--title">Сотрудники</span>
                        </a>
                    </div>
                    @endif

                </div>
            </div>
        </nav>
        <!-- /main sidebar -->


        <!-- Main navbar -->
        <header class="navbar">

            <div class="navbar__logo">
                <img src="/img/sidebar/logo.svg" alt="logo" class="logo">
            </div>

            <div class="navbar__search">
                <div class="search__logo">
                        <i class="fa fa-search" aria-hidden="true"></i>
                </div>
                <input id="search-customer" type="search" name="search" class="search__input" placeholder="Поиск клиента">
                <div class="result-search"></div>
            </div>

            <div class="navbar__user-menu">
                <div class="user-menu__name-wrapper">
                    <div class="user-menu__icon">
                            <i class="dropdown--icon fa fa-chevron-left"></i>
                    </div>
                    <div class="user-menu__name-area">
                            <span class="user--name">{{Auth::user()->name}}</span>
                    </div>
                </div>
                <div class="user-menu__avatar">
                        <img src="/img/sidebar/avatar-default-min.png" alt="avatar" class="user--avatar">
                </div>
                <div class="navbar__toggle-menu">
                    <div class="toggle-menu__header">
                        <div class="menu__avatar-part">
                            <img src="/img/living-tape/avatar-main.png" alt="avatar" class="avatar-menu">
                        </div>
                        <div class="menu__name-part">
                            <span class="menu--user-name">{{Auth::user()->name}}</span>
                        </div>
                    </div>
                    <div class="toggle-menu__footer">
                        <button class="btn btn--menu-default">Изменить фото</button>
                        @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
                        <a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" class="btn btn--menu-logout">Выход</a>
                        @else
                        <a class="btn btn--menu-logout" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Выход</a>
                            <form id="logout-form"
                                    action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}"
                                    method="POST" style="display: none;">
                                @if(config('adminlte.logout_method'))
                                    {{ method_field(config('adminlte.logout_method')) }}
                                @endif
                                {{ csrf_field() }}
                            </form>
                        @endif

                    </div>
                </div>
            </div>
            
        </header>
    <!-- /main navbar -->

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                @yield('content')
            </section>
            <!-- /.Main content -->
        </div>
        <!-- /.content-wrapper -->
    <!-- ./wrapper -->

@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/search-customer.js') }}"></script>
    <script src="{{ asset('js/chart.min.js') }}"></script>

    <script>
        CONFIG_JS = {csrfToken: '{{csrf_token()}}'}
    </script>

    @stack('js')
    @yield('js')
@stop
