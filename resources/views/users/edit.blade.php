@extends('adminlte::page')

@section('content_header')
    <div class="title-page">
        <h1 class="title-page__name"><span style="font-size: 20px">Сотрудник <i>{{$user->name}}</i></span></h1>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="row">
            <div class="col-md-4">
                <a href="{{route('users')}}" class="btn btn-sm btn-default">Back</a>
            </div>
        </div>
        <div class="col-md-6 bg bg-gray-light" style="border-radius: 5px">
            <form id="userEdit" method="post">
                {{method_field('PUT')}}
                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                <input type="hidden" id="user_id" value="{{$user->id}}">
                <div class="row">
                    <div class="form-group col-md-12">
                        <div class="col-md-4">
                            <label for="name">ФИО</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" value="{{$user->name}}">
                        </div>
                        <div class="col-md-4">
                            <label for="user_email">Email</label>
                            <input type="email" class="form-control" id="user_email" name="user_email" value="{{$user->email}}">
                        </div>
                        <div class="col-md-4">
                            <label for="user_telegram">Telegram</label>
                            <input type="text" class="form-control" id="user_telegram" name="user_telegram" value="{{$user->telegram_id}}">
                        </div>
                        <div class="col-md-4">
                            <label for="user_date_employment">Принят на работу</label>
                            <input type="date" class="form-control" id="user_date_employment" name="user_date_employment"
                                   value="{{$user->date_employment ? \Carbon\Carbon::make($user->date_employment)->format('Y-m-d') : ''}} ">
                        </div>
                        <div class="col-md-4">
                            <label for="user_role">Роль</label>
                            <select class="form-control" name="user_role" required id="user_role">
                                @if($user->role_id)
                                    <option value="{{$user->role_id}}">{{$user->role->name}}</option>
                                    @foreach($roles as $role)
                                        @if($role->id != $user->role->id)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option disabled selected>Выберите роль</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                @endif

                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="user_group">Група</label>
                            <select class="form-control" name="user_group" required id="user_group">
                                @if($user->group_id)
                                    <option value="{{$user->group_id}}" selected>{{$user->group->name}}</option>
                                    @foreach($groups as $group)
                                        @if($group->id != $user->group->id)
                                            <option value="{{$group->id}}">{{$group->name}}</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option disabled selected>Выберите группу</option>
                                    @foreach($groups as $group)
                                        <option value="{{$group->id}}">{{$group->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="user_branch">Филиал</label>
                            <select class="form-control" name="user_branch" required id="user_branch">
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="user_regions">Регион</label><br/>
                            <select name="user_regions[]" id="user_regions"
                                    class="multiselect-ui form-control form-control-sm" multiple="multiple">
                                @foreach($regions as $region)
                                    <option value="{{$region->id}}" {{!empty($user->regionsNew()->where('region_id', $region->id)->count()) ? 'selected' : ''}}>{{$region->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-8">
                        <div class="col-md-4">
                            <button id="userEditSubmit" class="btn btn-sm btn-success">Изменить</button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="userUpdateResult"></div>
        </div>
    </div>
@endsection

@section('tmp_js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="{{asset('js/users/userIndex.js')}}"></script>
    <script src="{{asset('js/users/userEdit.js')}}"></script>
@endsection

