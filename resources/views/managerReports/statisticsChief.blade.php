@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <h1 class="header-stat" style="color: white">Статистика</h1>
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">

@stop

@section('content')
    <div class="row">
        <div class="form-group">
            <h4>Укажите дату</h4>
            <div class="form_group_date">
                <label>
                    <input type="date" class="form-control" name="dateFrom" id="statDateFrom"
                           value="{{\Carbon\Carbon::make( \Carbon\Carbon::now()->format('Y-m') . '-01')->format('Y-m-d')}}"
                           placeholder="date">
                </label>
                <label>
                    <input type="date" class="form-control" name="dateTo" id="statDateTo"
                           value="{{\Carbon\Carbon::now()->format('Y-m-d')}}"
                           placeholder="date">
                </label>
            </div>
        </div>
        <div class="form-group">
            @include('leads.filter.leadFilterOption')
        </div>

        <div class="col-md-12">

            {{--<input class="form-control" type="text" id="citySearch" placeholder="Точка продаж" style="width: 230px;">--}}

            <table class="table table-hover table-striped table-bordered" id="summaryReportTable">
                <thead>
                <tr class="bg-green">
                    <th rowspan="2"><h5>Точка продаж</h5></th>
                    <th colspan="2"><h5>План</h5></th>
                    <th rowspan="2"><h5>
                            Посетители</h5></th>
                    <th colspan="2"><h5>Звонки</h5></th>
                    {{--<th colspan="3"><h5>Лиды</h5></th>--}}
                    {{--<th colspan="2"><h5>Просчеты</h5></th>--}}
                    {{--<th colspan="2"><h5>Замеры</h5></th>--}}
                    {{--<th colspan="3"><h5>Выставленные--}}
                    {{--счета</h5></th>--}}
                    <th colspan="3"><h5>Оплата</h5></th>
                    <th colspan="2"><h5>Процент выполнения</h5></th>
                </tr>
                <tr class="bg-green">
                    <th><h6>конструкции, шт.</h6></th>
                    <th><h6>сумма,грн.</h6></th>
                    <th><h6>входящие</h6></th>
                    <th><h6>исходящие</h6></th>
                    {{--<th><h6>пропущенные</h6></th>--}}
                    {{--<th class="head_form_title"><h6>Всего</h6></th>--}}
                    {{--<th class="head_form_title"><h6>В работе</h6></th>--}}
                    {{--<th class="head_form_title"><h6>Отработано</h6></th>--}}
                    {{--<th><h6>количество</h6></th>--}}
                    {{--<th><h6>сумма</h6></th>--}}
                    {{--<th><h6>количество замеров</h6></th>--}}
                    {{--<th><h6>количество конструкций</h6></th>--}}
                    {{--<th><h6>количество</h6></th>--}}
                    {{--<th><h6>конструкций, шт.</h6></th>--}}
                    {{--<th><h6>сумма</h6></th>--}}
                    <th><h6>количество</h6></th>
                    <th><h6>конструкций, шт.</h6></th>
                    <th><h6>сумма</h6></th>
                    <th><h6>конструкций</h6></th>
                    <th><h6>сумма</h6></th>
                </tr>
                </thead>
                <tfoot>
                <tr class="bg bg-gray">
                    <td><h4>Итого:</h4></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    {{--<td></td>--}}
                    {{--<td></td>--}}
                    {{--<td></td>--}}
                    {{--<td></td>--}}
                    {{--<td></td>--}}
                    {{--<td></td>--}}
                    {{--<td></td>--}}
                    {{--<td></td>--}}
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
@section('users_js')
    <script src="{{asset('js/users/userIndex.js')}}"></script>
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>

    <script src="{{asset('js/lead/leadFilter.js')}}"></script>
    <script src="{{asset('js/statistics/dataTable.js')}}"></script>
@endsection