@extends('adminlte::page')

@section('content_header')
    <div class="title-page">
        <h1 class="title-page__name">Сотрудники</h1>
    </div>
@endsection

@section('content')
    @include('users.modal.restart2fa')
    @include('users.modal.transferUserData')
    <div class="row">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
        <span class='filter--title'>Группа</span>
        <label>
            <select name="group_id[]" id="userGroup"
                    class="multiselect-ui form-control form-control-sm" multiple="multiple">
                @foreach($groups as $group)
                    <option value="{{$group->id}}">{{$group->name}}</option>
                @endforeach
                <option value="0">Не установленна</option>
            </select>
        </label>
        <label>
            <select class="form-control" name="fired" id="userWork">
                <option value="1" selected>Работает</option>
                <option value="0">Уволен</option>
            </select>
        </label>
    </div>
    @include('users.support.usersTab')
@endsection

@section('tmp_js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="{{asset('js/users/userIndex.js')}}"></script>
    <script src="{{asset('js/dataTables/userDataTables.js')}}"></script>
@endsection

