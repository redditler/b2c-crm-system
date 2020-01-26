@extends('adminlte::page')

@section('users_css')
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
@endsection

@section('content')
    <div class="row">
        <h1 class="content_header__h1" style="margin: -16px 0px; width: 330px;">Органайзер</h1>
    </div>

    <div class="container" id="container-for-organizer">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
        {{--@if(!(\Illuminate\Support\Facades\Auth::user()->role_id == 3 || \Illuminate\Support\Facades\Auth::user()->role_id == 5))--}}
        {{--<div class="row">--}}
        {{--<div class="col-md-2" id="selectUserShow">--}}
        {{--<label for="selectUserShowSelect">Сотрудники: </label><br/>--}}

        {{--<select name="client_user_id[]" id="selectUserShowSelect"--}}
        {{--class="multiselect-ui form-control form-control-sm" multiple="multiple">--}}
        {{--@foreach(\App\User::getEventUserTree(\Illuminate\Support\Facades\Auth::user()) as $value)--}}
        {{--<option value="{{$value->id}}">{{$value->name}}</option>--}}
        {{--@endforeach--}}
        {{--</select>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--@endif--}}
        @include('organizer.addFullCalendar')
        @if(\Illuminate\Support\Facades\Auth::user()->role_id != 3 && \Illuminate\Support\Facades\Auth::user()->role_id != 5)
            @include('leads.filter.leadFilterOption')
            <button class="btn" id="organizerFilterExecute">Применить</button>
        @endif
        @include('organizer.showEvent')
        @include('organizer.eventFilter')
        <br/>
        <div class="row" id="style-for-organizer">
            <div class="col-md-12">
                <div class="panel panel-default center-item">
                    <div class="panel-body" id="eventCalendar">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('tmp_js')
<script src="{{asset('js/components/multiselect.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/ru.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/locale/ru.js"></script>
    <script src="{{asset('js/fullCalendar/fullCalendarMyScript.js')}}"></script>
    <script src="{{asset('js/lead/leadFilter.js')}}"></script>
    

@endsection