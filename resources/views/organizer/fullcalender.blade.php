@extends('adminlte::page')

@section('users_css')
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.css') }}" type="text/css"/>
    
@endsection

@section('content')
    <section class="content__wrapper title-style" data-id="organizer">
        <div class="container organizer">
            <div class="container__title">
                <h1 class="title">Органайзер</h1>
            </div>

            <div class="container__content">
                <div class="content__header">
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                    @include('organizer.addFullCalendar')

                    @if(\Illuminate\Support\Facades\Auth::user()->role_id != 3 && \Illuminate\Support\Facades\Auth::user()->role_id != 5)
                        @include('leads.filter.leadFilterOption')
                        <button class="btn btn--default" id="organizerFilterExecute">Применить</button>
                    @endif

                    @include('organizer.showEvent')
                    @include('organizer.eventFilter')

                </div>

                <div class="content__body">
                    <div class="panel-body" id="eventCalendar"></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('tmp_js')
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" type="text/css"/>
    <script type="text/javascript" src="{{ asset('js/bootstrap-multiselect.js ') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/ru.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/locale/ru.js"></script>
    <script src="{{asset('js/fullCalendar/fullCalendarMyScript.js')}}"></script>
    <script src="{{asset('js/lead/leadFilter.js')}}"></script>
    <script src="{{asset('js/components/colorpicker.js')}}"></script>
    <script>
        $(document).ready(function(){
            colorpicker();
        });
    </script>
@endsection