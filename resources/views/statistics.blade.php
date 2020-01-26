@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <h1 class="header-stat">Статистика</h1>
@stop

@section('content')
    <div class="nav-tabs-custom nav-tabs-stat">
        {{--<div class="row" id="statistic-nav-bar">--}}
        {{--<div class="col-md-12">--}}
        {{--<div class="col-md-2 pull-right" style="height: 59px">--}}
        {{--{!! Form::select('months', $months, null, array('id' => 'months', 'disabled', 'class' => 'form-control', 'style' => 'position: absolute;bottom: 0; width: 84%;')) !!}--}}
        {{--</div>--}}
        {{--<div class="col-md-1 pull-right" style="height: 59px">--}}
        {{--<a href="javascript:void(0);" id="month" style="position: absolute;bottom: 0;">За месяц</a>--}}
        {{--</div>--}}
        {{--<div class="col-md-1 pull-right" style="height: 59px">--}}
        {{--<a href="javascript:void(0);" id="year" style="position: absolute;bottom: 0;">За год</a>--}}
        {{--</div>--}}
        {{--<div class="col-md-1 pull-right" style="height: 59px">--}}
        {{--<a href="javascript:void(0);" id="today" style="position: absolute;bottom: 0;text-decoration:underline">За сегодня</a>--}}
        {{--</div>--}}
        {{--<div class="col-md-1 pull-right" style="height: 59px">--}}
        {{--<a href="javascript:void(0);" id="yesterday" style="position: absolute;bottom: 0;">За вчера</a>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<input type="text" id="date_stat" value="{{date('Y-m-d')}}" hidden>--}}
        {{--<input type="text" id="month_stat" hidden>--}}
        {{--<input type="text" id="year_stat" hidden>--}}
        {{--</div>--}}

        <div id="statistic-nav-bar" class="stat-nav-bar">
            @if(!Auth::user()->manager)
                <div class="stat-nav-bar__right-menu">
                    {!! Form::label('branch_id', 'Филиал' , ['class' => 'col-md-3 control-label']); !!}
                </div>
                <div class="stat-nav-bar__right-menu">
                    {!! Form::select('branch_id', $branches, null, ['id' => 'branch_id', 'class' => 'form-control']) !!}
                </div>
            @endif
            <div class="stat-nav-bar__right-menu">
                <a href="javascript:void(0);" id="year">За год</a>
            </div>
            <div class="stat-nav-bar__right-menu">
                <a href="javascript:void(0);" id="today">За сегодня</a>
            </div>
            <div class="stat-nav-bar__right-menu">
                <a href="javascript:void(0);" id="yesterday">За вчера</a>
            </div>
            <div class="stat-nav-bar__right-menu">
                <a href="javascript:void(0);" id="month">За месяц</a>
            </div>
            <div class="stat-nav-bar__right-menu">
                {!! Form::select('months', $months, null, array('id' => 'months', 'disabled', 'class' => 'form-control')) !!}
            </div>
            <input type="text" id="date_stat" value="{{date('Y-m-d')}}" hidden>
            <input type="text" id="month_stat" hidden>
            <input type="text" id="year_stat" hidden>
        </div>
        <ul class="nav nav-tabs" id="stat-tabs">
            <li class="active"><a href="#daily-tab" data-toggle="tab" aria-expanded="true">Ежедневный отчет</a></li>
            <li class=""><a href="#fin-tab" data-toggle="tab" aria-expanded="false">Финансовый отчет</a></li>
        </ul>
        <div class="tab-content stat_tables">
            <div class="tab-pane active" id="daily-tab">
                <div class="row" style="overflow: auto">
                    <div class="col-md-12">
                        <table id="done-leeds-table" class="table table-responsive">
                            <thead>
                            <tr>
                                <td colspan="3">План</td>
                                <td colspan="2">Клиенты</td>
                                <td colspan="2">Звонки</td>
                                <td colspan="2">Просчеты</td>
                                <td>Замеры</td>
                                <td colspan="3">Счета</td>
                                <td colspan="3">Оплаты</td>

                                <td colspan="2">Факт</td>
                                <td colspan="2">Выполнение плана%</td>

                            </tr>
                            <tr>
                                <td style="color: black;">Филиалы</td>
                                <td style="color: black;">Конструкции</td>
                                <td style="color: black;">Сумма грн.</td>

                                <td style="color: black;">Обработанных лидов</td>
                                <td style="color: black;">Посетители</td>

                                <td style="color: black">Входящие</td>
                                <td style="color: black">Исходящие</td>

                                <td style="color: black">Количество</td>
                                <td style="color: black">Конструкции</td>

                                <td style="color: black">Конструкции</td>

                                <td style="background-color: #51dd84;color: black ">Количество</td>
                                <td style="color: black">Конструкции</td>
                                <td style="color: black">Сумма грн.</td>

                                <td style="color: black">Количество</td>
                                <td style="color: black">Конструкции</td>
                                <td style="color: black">Сумма грн.</td>

                                <td style="color: black">Конструкции</td>
                                <td style="color: black">Сумма грн.</td>

                                <td style="color: black">Конструкции</td>
                                <td style="color: black">Сумма грн.</td>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="fin-tab">
                <div class="row">
                    <div class="col-md-12">
                        <table id="fin-plan-table" class="display responsive no-wrap fin-plan-table-reset"
                               cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <td>Дата</td>
                                <td>Заказ</td>
                                <td>Сумма</td>
                                <td>Кол-во кон-ций</td>
                                <td>Скидка</td>
                                <td>Заказчик</td>
                                <td>Город</td>
                                <td>Адрес</td>
                                <td>Телефон</td>
                                <td>Почта</td>
                                <td>Монтажник</td>
                                <td>Площадь</td>
                                <td>Сумма</td>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>
@stop


@section('tmp_js')
    <script>
        table = null;
        table_2 = null;
        $(document).ready(function () {
            var table = $('#done-leeds-table').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "<?php echo route('get-stat-daily-reports') ?>",
                    "method": "POST",
                    "data": function (d) {
                        d._token = $("input[name='_token']").val();
                        d.date_stat = $("#date_stat").val();
                        d.month_stat = $("#month_stat").val();
                        d.year_stat = $("#year_stat").val();
                        d.branch_id = $("#branch_id").val();
                    }
                },
                columns: [
                    {data: 'branch_id', name: 'branch_id', width: "5%"},
                    {data: 'framework_plan', name: 'framework_plan', width: "7%"},
                    {data: 'sum_plan', name: 'sum_plan', width: "5%"},

                    {data: 'count_done_leeds', name: 'count_done_leeds',},
                    {data: 'count_clients', name: 'count_clients',},

                    {data: 'count_in_calls', name: 'count_in_calls',},
                    {data: 'count_out_calls', name: 'count_out_calls'},

                    {data: 'count_culations', name: 'count_culations'},
                    {data: 'count_framework_culations', name: 'count_framework_culations'},

                    {data: 'direct_sample', name: 'direct_sample'},

                    {data: 'count_bills', name: 'count_bills'},
                    {data: 'count_framework_bills', name: 'count_framework_bills'},
                    {data: 'common_sum_bills', name: 'common_sum_bills'},

                    {data: 'count_payments', name: 'count_payments'},
                    {data: 'count_framework_payments', name: 'count_framework_payments'},
                    {data: 'common_sum_payments', name: 'common_sum_payments'},

                    {data: 'framework_fact', name: 'framework_fact', width: "7%"},
                    {data: 'sum_fact', name: 'sum_fact', width: "5%"},

                    {data: 'framework_percent', name: 'framework_percent', width: "7%"},
                    {data: 'sum_percent', name: 'sum_percent', width: "5%"},
                ],
                language: {
                    "processing": "Подождите...",
                    "search": "Поиск:",
                    "lengthMenu": "Показать _MENU_ записей",
                    "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                    "infoEmpty": "Записи с 0 до 0 из 0 записей",
                    "infoFiltered": "(отфильтровано из _MAX_ записей)",
                    "infoPostFix": "",
                    "loadingRecords": "Загрузка записей...",
                    "zeroRecords": "Записи отсутствуют.",
                    "emptyTable": "В таблице отсутствуют данные",
                    "paginate": {
                        "first": "Первая",
                        "previous": "",
                        "next": "",
                        "last": "Последняя"
                    },
                    "aria": {
                        "sortAscending": ": активировать для сортировки столбца по возрастанию",
                        "sortDescending": ": активировать для сортировки столбца по убыванию"
                    }
                }
            });


            var table_2 = $('#fin-plan-table').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "<?php echo route('get-fin-plans') ?>",
                    "method": "POST",
                    "data": function (d) {
                        d._token = $("input[name='_token']").val();
                        d.date_stat = $("#date_stat").val();
                        d.month_stat = $("#month_stat").val();
                        d.year_stat = $("#year_stat").val();
                        d.branch_id = $("#branch_id").val();
                    }
                },
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'num_order', name: 'num_order'},
                    {data: 'sum_order', name: 'sum_order'},
                    {data: 'framework_count', name: 'framework_count'},
                    {data: 'discount', name: 'discount'},
                    {data: 'name', name: 'name'},
                    {data: 'city', name: 'city'},
                    {data: 'adres', name: 'adres', orderable: false, searchable: false},
                    {data: 'phone', name: 'phone'},
                    {data: 'email', name: 'email'},
                    {data: 'installer', name: 'installer'},
                    {data: 'area', name: 'area'},
                    {data: 'sum', name: 'sum'}

                ],
                language: {
                    "processing": "Подождите...",
                    "search": "Поиск:",
                    "lengthMenu": "Показать _MENU_ записей",
                    "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                    "infoEmpty": "Записи с 0 до 0 из 0 записей",
                    "infoFiltered": "(отфильтровано из _MAX_ записей)",
                    "infoPostFix": "",
                    "loadingRecords": "Загрузка записей...",
                    "zeroRecords": "Записи отсутствуют.",
                    "emptyTable": "В таблице отсутствуют данные",
                    "paginate": {
                        "first": "Первая",
                        "previous": "",
                        "next": "",
                        "last": "Последняя"
                    },
                    "aria": {
                        "sortAscending": ": активировать для сортировки столбца по возрастанию",
                        "sortDescending": ": активировать для сортировки столбца по убыванию"
                    }
                }
            });


            $("#months").change(function () {
                var selected_month = $("#months").val();
                $("#month_stat").val(selected_month);
                var date = new Date();
                var year = date.getFullYear();
                $("#year_stat").val(year);
                table.ajax.reload();
                table_2.ajax.reload();
            });
            $("#branch_id").change(function () {
                table.ajax.reload();
                table_2.ajax.reload();
            });

            function disableMonth(bool) {
                $('#months option[value="0"]').prop('selected', true);
                $('#months').prop('disabled', bool);
            }

            $("#statistic-nav-bar a").click(function () {

                $("#statistic-nav-bar a").css('text-decoration', 'none');

                $("#date_stat").val('');
                $("#month_stat").val('');
                $("#year_stat").val('');

                if ($(this).attr('id') == 'month') {
                    disableMonth(false);
                } else if ($(this).attr('id') == 'year') {
                    disableMonth(true);
                    var date = new Date();
                    var year = date.getFullYear();
                    $("#year_stat").val(year);
                    table.ajax.reload();
                    table_2.ajax.reload();
                } else if ($(this).attr('id') == 'today') {
                    disableMonth(true);
                    var date_now = $.datepicker.formatDate('yy-mm-dd', new Date());
                    $("#date_stat").val(date_now);
                    table.ajax.reload();
                    table_2.ajax.reload();
                } else if ($(this).attr('id') == 'yesterday') {
                    disableMonth(true);

                    var date = new Date();
                    date.setDate(date.getDate() - 1);
                    var yesterday = $.datepicker.formatDate('yy-mm-dd', date);
                    $("#date_stat").val(yesterday);
                    table.ajax.reload();
                    table_2.ajax.reload();
                }
                $(this).css('text-decoration', 'underline');
            });
            $(".dataTables_filter").hide();
            $(".dataTables_length").hide();
            $(".dataTables_info").hide();
        });
    </script>
@endsection