@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    @php
     /*  use App\DailyReport;use App\Support\UserRole\SelectRole;use Carbon\Carbon;use Illuminate\Support\Facades\Auth;
        dd(SelectRole::selectRole(Auth::user())->getUserSalon());*/
    @endphp

    <h1 class="header-stat">Отчеты менеджеров</h1>
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">

@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <a href="{{route('managerReports.create')}}" class="btn btn-success">Добавить</a>
            <form id="leadDatePicker" class='filter-menu__filter'>
                <div class="filter__name"><span>Дата</span></div>
                <div class="filter__date">
                    <label class='filter--date-from'>
                        <div><span>c</span><i class="fa fa-angle-down"></i></div>
                        <input type="text" class='form-control filter--date' name="dateFrom" id="dateFrom"
                               class="form-control input-sm"
                               value="{{\Carbon\Carbon::make(session()->get('leadDateFrom'))->format('Y-m-d')}}">
                    </label>
                    <label class='filter--date-to'>
                        <div><span>по</span><i class="fa fa-angle-down"></i></div>
                        <input type="text" class='form-control filter--date' name="dateTo" id="dateTo"
                               class="form-control input-sm"
                               value="{{\Carbon\Carbon::make(session()->get('leadDateTo'))->format('Y-m-d')}}">
                    </label>
                </div>
                @include('leads.filter.leadFilterOption')
            </form>
            <table class="table lenta-table text-center" id="managerReports">
                <thead>
                <tr>
                    <th>
                        <div>Действие</div>
                    </th>
                    <th>
                        <div>Дата</div>
                    </th>
                    <th>
                        <div>Точка продаж</div>
                    </th>
                    <th>
                        <div>Менеджер</div>
                    </th>

                    <th>
                        <div>Кол-во выстав. счетов</div>
                    </th>
                    <th>
                        <div>Кол-во кон-ций в счетах</div>
                    </th>
                    <th>
                        <div>Общая сумма в счетах</div>
                    </th>

                    <th>
                        <div>Кол-во оплат</div>
                    </th>
                    <th>
                        <div>Кол-во кон-ций в оплатах</div>
                    </th>
                    <th>
                        <div>Сумма в оплатах</div>
                    </th>
                </tr>
                </thead>

            </table>
        </div>
    </div>
@endsection
@section('users_js')
    <script src="{{asset('js/users/userIndex.js')}}"></script>
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('js/lead/leadFilter.js')}}"></script>
    <script>
        $(document).ready(function () {

            // Добавление пользовательского календаря
            $(".filter--date").datepicker({
                monthNames: ['Январь', 'Февраль', 'Март', 'Апрель',
                    'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь',
                    'Октябрь', 'Ноябрь', 'Декабрь'],
                dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                firstDay: 1,
                showAnim: "drop",
                dateFormat: 'yy-mm-dd',
            });


            $("#dateFrom").change(function () {
                tableReports.ajax.reload();
            });
            $("#dateTo").change(function () {
                tableReports.ajax.reload();
            });

            $('#sectorGroupFilter').delegate('select', 'change', function () {
                tableReports.ajax.reload();
            });
            $('#sectorRegionManagerFilter ').delegate('select', 'change', function () {
                tableReports.ajax.reload();
            });
            $('#sectorSalonFilter ').delegate('select', 'change', function () {
                tableReports.ajax.reload();
            });
            $('#sectorManagerFilter ').delegate('select', 'change', function () {
                tableReports.ajax.reload();
            });

            let tableReports = $('#managerReports').DataTable({
                //order: [['1', "desc"]],
                "pageLength": 25,
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/managerReportTable',
                    "method": "POST",
                    'data': function (d) {
                        d.dateFrom = $('#dateFrom').val();
                        d.dateTo = $('#dateTo').val();

                        d.group_id = $('#leadGroupSelector').val();
                        d.regionManager_id = $('#leadRegionManagerSelector').val();
                        d.salon_id = $('#leadSalon').val();
                        d.user_id = $('#leadManagerSelector').val();
                    }
                },
                columns:[
                    {data: 'action', name: 'action'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'branch_id', name: 'branch_id'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'count_bills', name: 'count_bills'},
                    {data: 'count_framework_bills', name: 'count_framework_bills'},
                    {data: 'common_sum_bills', name: 'common_sum_bills'},
                    {data: 'count_payments', name: 'count_payments'},
                    {data: 'count_framework_payments', name: 'count_framework_payments'},
                    {data: 'common_sum_payments', name: 'common_sum_payments'},
                ],
                language: {
                    "processing": "Подождите...",
                    "search": "",
                    "lengthMenu": "Показать по _MENU_ записей",
                    "info": "_TOTAL_ записей",
                    "infoEmpty": "Записи с 0 до 0 из 0 записей",
                    "infoFiltered": "(отфильтровано из _MAX_ записей)",
                    "infoPostFix": "",
                    "loadingRecords": "Загрузка записей...",
                    "zeroRecords": "Записи отсутствуют.",
                    "emptyTable": "В таблице отсутствуют данные",
                    "paginate": {
                        "first": "Первая",
                        "previous": "<",
                        "next": ">",
                        "last": "Последняя"
                    },
                    "aria": {
                        "sortAscending": ": активировать для сортировки столбца по возрастанию",
                        "sortDescending": ": активировать для сортировки столбца по убыванию"
                    }
                },
            });
        });
    </script>
    <style type="text/css">

        .row, .wrapper, body {
            overflow: visible !important;
        }

        .dataTables_scrollHead {
            position: sticky !important;
            top: 0px;
            z-index: 99;
            overflow: inherit !important;
        }

        .dataTables_scrollBody {
            overflow: inherit !important;
        }

        .dataTables_scrollHeadInner {
            background: #fff;
        }

        #managerReports_filter {
            width: inherit;
            margin: 0;
        }

        /*        #managerReports td, #managerReports th {
                    padding: 0!important;
                }*/
    </style>
@endsection
