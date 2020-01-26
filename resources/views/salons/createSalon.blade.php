@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <h1 class="header-stat">Создать точку продаж</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="{{route('salons.store')}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label>Выберите группу
                            <select class="form-control" name="group" required>
                                <option disabled selected>Выберите группу</option>
                                @foreach($groups as $group)
                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>Выберите регион
                            <select class="form-control" name="region" required>
                                <option disabled selected>Выберите регион</option>
                                @foreach($regions as $region)
                                    <option value="{{$region->id}}">{{$region->name}}</option>
                                @endforeach
                            </select>
                        </label>

                        <label>Slug (заполнять латинскими символами)
                            <input class="form-control" type="text" name="slug" value="{{old('slug')}}" required
                                   placeholder="Заполнять латинскими символами">
                        </label>

                        <label>Название
                            <input class="form-control" type="text" name="name" value="{{old('name')}}" required
                                   placeholder="Введите название">
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Адрес
                            <input class="form-control" type="text" name="address" value="{{old('address')}}"
                                   required placeholder="Введите адрес">
                        </label>
                        <label>Контактный номер
                            <input class="form-control" type="tel" name="phone" id="salonCreatePhone"
                                   value="{{old('phone')}}"
                                   required placeholder="Введите номер">
                        </label>
                        <label>Дата открытия точки продаж
                            <input class="form-control hasDatepicker" type="date" name="date_opening"
                                   value="{{\Carbon\Carbon::now()->format('Y-m-d')}}"
                                   required>
                        </label>
                        <label>Код kb
                            <input class="form-control hasDatepicker" type="number" name="code_kb"
                                   value="{{old('code_kb')}}"
                                   required>
                        </label>
                    </div>
                    <button class="btn btn-success">Создать</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('users_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#salonCreatePhone").mask("(999) 999-99-99");
        })
    </script>
@endsection