@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <h1 class="header-stat">Точка продаж {{$branch->name}}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="{{route('salons.update', $branch->id)}}" method="post">
                    {{csrf_field()}}
                    {{method_field('PUT')}}
                    <div class="form-group">
                        <label>Выберите группу
                            <select class="form-control" name="group" required>
                                @if(!empty($branch->group_id))
                                    <option value="{{$branch->group_id}}">{{$branch->groups->name}}</option>
                                @else
                                    <option disabled>Выберите группу</option>
                                @endif
                                @foreach($groups as $group)
                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>Выберите регион
                            <select class="form-control" name="region" required>
                                @if(isset($branch->region_id))
                                    <option value="{{$branch->region_id}}">{{$branch->name}}</option>
                                @else
                                    <option disabled>Выберите регион</option>
                                @endif
                                @foreach($regions as $region)
                                    <option value="{{$region->id}}">{{$region->name}}</option>
                                @endforeach
                            </select>
                        </label>

                        <label>Slug (заполнять латинскими символами)
                            <input class="form-control" type="text" name="slug" value="{{$branch->slug}}" required>
                        </label>
                        <label>Название
                            <input class="form-control" type="text" name="name" value="{{$branch->name}}" required>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Адрес
                            <input class="form-control" type="text" name="address" value="{{$branch->address}}"
                                   required>
                        </label>
                        <label>Контактный номер
                            <input class="form-control" type="tel" name="phone" id="salonEditPhone"
                                   value="{{!is_null($branch->phone) ? $branch->phone : old('phone')}}"
                                   required placeholder="Введите номер">
                        </label>
                        <label>Дата открытия точки продаж
                            <input class="form-control hasDatepicker" type="date" name="date_opening"
                                   value="{{$branch->date_opening ? \Carbon\Carbon::make($branch->date_opening)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')}}"
                                   required>
                        </label>
                        <label>Код kb
                            <input class="form-control hasDatepicker" type="number" name="code_kb"
                                   value="{{$branch->code_kb}}"
                                   required>
                        </label>
                    </div>
                    <button class="btn btn-success">Изменить</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('users_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#salonEditPhone").mask("(999) 999-99-99");
        })
    </script>
@endsection