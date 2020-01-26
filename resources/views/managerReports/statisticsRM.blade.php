@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <h1 class="header-stat">Статистика</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row" style="overflow: auto">
            <form action="{{route('statisticsRm')}}" method="post" id="statDateForm">
                {{csrf_field()}}
                <div class="form-group">
                    <label>Укажите дату
                        <input type="date" class="form-control" name="dateFrom"
                               value=""
                               placeholder="date">
                    </label>
                    <label>
                        <input type="date" class="form-control" name="dateTo"
                               value=""
                               placeholder="date">
                    </label>
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </form>
            <div class="bs-example bs-example-tabs  text-center" data-example-id="togglable-tabs">
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#summaryReport" id="summaryReport-tab" role="tab" data-toggle="tab"
                           aria-controls="summaryReport" aria-expanded="true">Сводная</a>
                    </li>
                    <li role="presentation" class=""><a href="#byDates" role="tab" id="byDates-tab" data-toggle="tab"
                                                        aria-controls="byDates" aria-expanded="false">По датам</a></li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade active in" role="tabpanel" id="summaryReport"
                         aria-labelledby="summaryReport-tab">
                        <table class="table table-bordered table-condensed tableBranch" id="summaryReportTable">
                            <tr class="bg-green">
                                <td rowspan="2"><h5 style="margin-top: 52px;">Точка продаж</h5></td>
                                <td rowspan="2"><h5 style="margin-top: 52px;">Дата</h5></td>
                                <td colspan="2"><h5>План</h5></td>
                                <td rowspan="2"><h5 style="margin: 56px -20px 0;transform: rotate(-90deg);">
                                        Посетители</h5></td>
                                <td colspan="3"><h5>Лиды</h5></td>
                                <td colspan="3"><h5>Звонки</h5></td>
                                <td colspan="2"><h5>Просчеты</h5></td>
                                <td colspan="2"><h5>Замеры</h5></td>
                                <td colspan="3"><h5>Выставленные счета</h5></td>
                                <td colspan="3"><h5>Оплата</h5></td>
                                <td colspan="2"><h5>Процент выполнения</h5></td>
                            </tr>
                            <tr class="bg-green headerBranch">
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -18px;">конструкции</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -2px;">сумма</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">Всего</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">В работе</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">Отработано</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">входящие</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -2px;">исходящие</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">пропущенные</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -18px;">количество</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">сумма</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -18px;">количество</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -11px;">конструкций</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">количество</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -18px;">конструкций</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -2px;">сумма</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">количество</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -18px;">конструкций</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -2px;">сумма</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -18px;">конструкций</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -2px;">сумма</h6></td>
                            </tr>


                        </table>
                    </div>
                    <div class="tab-pane fade" role="tabpanel" id="byDates" aria-labelledby="byDates-tab">
                        <table class="table table-bordered table-condensed" id="byDatesTable">
                            <tr class="bg-green">
                                <td rowspan="2"><h5 style="margin-top: 52px;">Дата</h5></td>
                                <td rowspan="2"><h5 style="margin-top: 52px;">Точка продаж</h5></td>
                                <td colspan="2"><h5>План</h5></td>
                                <td rowspan="2"><h5 style="margin: 56px -20px 0;transform: rotate(-90deg);">
                                        Посетители</h5></td>
                                <td colspan="3"><h5>Лиды</h5></td>
                                <td colspan="3"><h5>Звонки</h5></td>
                                <td colspan="2"><h5>Просчеты</h5></td>
                                <td colspan="2"><h5>Замеры</h5></td>
                                <td colspan="3"><h5>Выставленные счета</h5></td>
                                <td colspan="3"><h5>Оплата</h5></td>
                                <td colspan="2"><h5>Процент выполнения</h5></td>
                            </tr>
                            <tr class="bg-green headerDay">
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -18px;">конструкции</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -2px;">сумма</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">Всего</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">В работе</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">Отработано</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">Входящие</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -2px;">Исходящие</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">Пропущенные</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -18px;">количество</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">сумма</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -18px;">количество</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -2px;">конструкций</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">количество</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -18px;">конструкций</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -2px;">сумма</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -9px;">количество</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -18px;">конструкций</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -2px;">сумма</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -18px;">конструкций</h6></td>
                                <td><h6 style="transform: rotate(-90deg);margin: 33px -2px;">сумма</h6></td>
                            </tr>


                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('users_js')
    <script type="text/javascript">
        $(document).ready(function () {
            //DATE
            let nowDate = new Date();
            let dayNow = (nowDate.getDate() < 10 ? '0' : '') + nowDate.getDate();
            let monthNow = ((nowDate.getMonth() + 1) < 10 ? '0' : '') + (nowDate.getMonth() + 1);
            let yearNow = nowDate.getFullYear();

            let dateFrom = $("input[name='dateFrom']").val(yearNow + "-" + monthNow + "-01");
            let dateTo = $("input[name='dateTo']").val(yearNow + "-" + monthNow + "-" + dayNow);

            //object from table
            let tableDays = JSON.parse('{!! $tableDays !!}');
            let tableBranches = JSON.parse('{!! $tableBranches!!}');
            let branches = JSON.parse('{!! $branches !!}');

            $(function () {
                /*
                * Table Branches
                */
                $.each(tableBranches, function (i, item) {
                    if (i != 'sum') {
                        $.each(item, function (j, date) {
                            if (j != 'sum') {
                                $('.headerBranch').after(`<tr class="bg-success showStat dateInfo${branches[i].id}" style="display: none">
                                                <td><h6></h6></td>
                                                <td><h6 >${j}</h6></td>
                                                <td><h6></h6></td>
                                                <td><h6>${date.planSum}</h6></td>
                                                <td><h6>${date.count_clients}</h6></td>
                                                <td><h6>${date.leedAll}</h6></td>
                                                <td><h6>${date.leedInWork}</h6></td>
                                                <td><h6></h6></td>
                                                <td><h6>${date.count_in_calls}</h6></td>
                                                <td><h6>${date.count_out_calls}</h6></td>
                                                <td><h6>${date.count_lost_calls}</h6></td>
                                                <td><h6>${date.count_culations}</h6></td>
                                                <td><h6>${date.common_culations}</h6></td>
                                                <td><h6>${date.direct_sample}</h6></td>
                                                <td><h6>${date.count_framework_culations}</h6></td>
                                                <td><h6>${date.count_bills}</h6></td>
                                                <td><h6>${date.count_framework_bills}</h6></td>
                                                <td><h6>${date.common_sum_bills}</h6></td>
                                                <td><h6>${date.count_payments}</h6></td>
                                                <td><h6>${date.count_framework_payments}</h6></td>
                                                <td><h6>${date.common_sum_payments}</h6></td>
                                                <td><h6></h6></td>
                                                <td><h6></h6></td>
                                            </tr>`);
                            }
                        });
                        $('.headerBranch').after(`<tr class="bg-gray showStat">
                                                <td><a href="#" id="dateOpen${branches[i].id}"><h6>${branches[i].name}</h6></a></td>
                                                <td><h6 style="width: 65px">${dateFrom.val()} - ${dateTo.val()}</h6></td>
                                                <td><h6>${item['sum'].planConstruct}</h6></td>
                                                <td><h6>${item['sum'].planSum}</h6></td>
                                                <td><h6>${item['sum'].count_clients}</h6></td>
                                                <td><h6>${item['sum'].leedAll}</h6></td>
                                                <td><h6>${item['sum'].leedInWork}</h6></td>
                                                <td><h6></h6></td>
                                                <td><h6>${item['sum'].count_in_calls}</h6></td>
                                                <td><h6>${item['sum'].count_out_calls}</h6></td>
                                                <td><h6>${item['sum'].count_lost_calls}</h6></td>
                                                <td><h6>${item['sum'].count_culations}</h6></td>
                                                <td><h6>${item['sum'].common_culations}</h6></td>
                                                <td><h6>${item['sum'].direct_sample}</h6></td>
                                                <td><h6>${item['sum'].count_framework_culations}</h6></td>
                                                <td><h6>${item['sum'].count_bills}</h6></td>
                                                <td><h6>${item['sum'].count_framework_bills}</h6></td>
                                                <td><h6>${item['sum'].common_sum_bills}</h6></td>
                                                <td><h6>${item['sum'].count_payments}</h6></td>
                                                <td><h6>${item['sum'].count_framework_payments}</h6></td>
                                                <td><h6>${item['sum'].common_sum_payments}</h6></td>
                                                <td><h6>${(item['sum'].planConstruct ? item['sum'].count_framework_payments / item['sum'].planConstruct * 100 : 0).toFixed(2)}%</h6></td>
                                                <td><h6>${(item['sum'].common_sum_payments ? item['sum'].common_sum_payments / item['sum'].planSum * 100 : 0).toFixed(2)}%</h6></td>
                                            </tr>`);

                        $(`#dateOpen${branches[i].id}`).click(function (e) {
                            e.preventDefault();
                            if ($(`.dateInfo${branches[i].id}`).css('display') == 'none') {
                                $(`.dateInfo${branches[i].id}`).fadeToggle(400, "linear", function () {
                                    $(`.dateInfo${branches[i].id}`).css("display", "table-row").css("transition-duration", "opacity 1s ease-out");
                                });
                            } else {
                                $(`.dateInfo${branches[i].id}`).fadeToggle(400, "linear", function () {
                                    $(`.dateInfo${branches[i].id}`).css("display", "none").css("transition-duration", "opacity 1s ease-out");
                                });
                            }
                        });

                    } else {
                        $('.tableBranch').append(`
                        <tr class="bg-green showStat">
                            <td colspan="2"><h5>Итого:</h5></td>
                            <td><h6>${item.planConstruct}</h6></td>
                            <td><h6>${item.planSum}</h6></td>
                            <td><h6>${item.count_clients}</h6></td>
                            <td><h6>${item.leedAll}</h6></td>
                            <td><h6>${item.leedInWork}</h6></td>
                            <td><h6>${item.count_done_leeds}</h6></td>
                            <td><h6>${item.count_in_calls}</h6></td>
                            <td><h6>${item.count_out_calls}</h6></td>
                            <td><h6>${item.count_lost_calls}</h6></td>
                            <td><h6>${item.count_culations}</h6></td>
                            <td><h6>${item.common_culations}</h6></td>
                            <td><h6>${item.direct_sample}</h6></td>
                            <td><h6>${item.count_framework_culations}</h6></td>
                            <td><h6>${item.count_bills}</h6></td>
                            <td><h6>${item.count_framework_bills}</h6></td>
                            <td><h6>${item.common_sum_bills}</h6></td>
                            <td><h6>${item.count_payments}</h6></td>
                            <td><h6>${item.count_framework_payments}</h6></td>
                            <td><h6>${item.common_sum_payments}</h6></td>
                            <td><h6>${(item.planConstruct ? item.count_framework_payments / item.planConstruct * 100 : 0).toFixed(2)}%</h6></td>
                            <td><h6>${(item.planSum ? item.common_sum_payments / item.planSum * 100 : 0).toFixed(2)}%</h6></td>
                        </tr>
                        `);
                    }
                });

                /*
                * Table Dates
                */

                //console.log(tableDays);
            });

            $('#statDateForm').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '/statisticsRm',
                    data: $('#statDateForm').serializeArray(),
                    success: function (result) {
                        let tableDays = result.tableDays;
                        let tableBranches = result.tableBranches;
                        let branches = result.branches;

                        $('.showStat').remove();
                        $(function () {
                            $.each(tableBranches, function (i, item) {
                                if (i != 'sum') {
                                    $.each(item, function (j, date) {
                                        if (j != 'sum') {
                                            $('.headerBranch').after(`<tr class="bg-success showStat dateInfo${branches[i].id}" style="display: none">
                                                <td><h6></h6></td>
                                                <td><h6 >${j}</h6></td>
                                                <td><h6></h6></td>
                                                <td><h6>${date.planSum}</h6></td>
                                                <td><h6>${date.count_clients}</h6></td>
                                                <td><h6>${date.leedAll}</h6></td>
                                                <td><h6>${date.leedInWork}</h6></td>
                                                <td><h6></h6></td>
                                                <td><h6>${date.count_in_calls}</h6></td>
                                                <td><h6>${date.count_out_calls}</h6></td>
                                                <td><h6>${date.count_lost_calls}</h6></td>
                                                <td><h6>${date.count_culations}</h6></td>
                                                <td><h6>${date.common_culations}</h6></td>
                                                <td><h6>${date.direct_sample}</h6></td>
                                                <td><h6>${date.count_framework_culations}</h6></td>
                                                <td><h6>${date.count_bills}</h6></td>
                                                <td><h6>${date.count_framework_bills}</h6></td>
                                                <td><h6>${date.common_sum_bills}</h6></td>
                                                <td><h6>${date.count_payments}</h6></td>
                                                <td><h6>${date.count_framework_payments}</h6></td>
                                                <td><h6>${date.common_sum_payments}</h6></td>
                                                <td><h6></h6></td>
                                                <td><h6></h6></td>
                                            </tr>`);
                                        }
                                    });
                                    $('.headerBranch').after(`<tr class="bg-gray showStat">
                                                <td><a href="#" id="dateOpen${branches[i].id}"><h6>${branches[i].name}</h6></a></td>
                                                <td><h6 style="width: 65px">${dateFrom.val()} - ${dateTo.val()}</h6></td>
                                                <td><h6>${item['sum'].planConstruct}</h6></td>
                                                <td><h6>${item['sum'].planSum}</h6></td>
                                                <td><h6>${item['sum'].count_clients}</h6></td>
                                                <td><h6>${item['sum'].leedAll}</h6></td>
                                                <td><h6>${item['sum'].leedInWork}</h6></td>
                                                <td><h6></h6></td>
                                                <td><h6>${item['sum'].count_in_calls}</h6></td>
                                                <td><h6>${item['sum'].count_out_calls}</h6></td>
                                                <td><h6>${item['sum'].count_lost_calls}</h6></td>
                                                <td><h6>${item['sum'].count_culations}</h6></td>
                                                <td><h6>${item['sum'].common_culations}</h6></td>
                                                <td><h6>${item['sum'].direct_sample}</h6></td>
                                                <td><h6>${item['sum'].count_framework_culations}</h6></td>
                                                <td><h6>${item['sum'].count_bills}</h6></td>
                                                <td><h6>${item['sum'].count_framework_bills}</h6></td>
                                                <td><h6>${item['sum'].common_sum_bills}</h6></td>
                                                <td><h6>${item['sum'].count_payments}</h6></td>
                                                <td><h6>${item['sum'].count_framework_payments}</h6></td>
                                                <td><h6>${item['sum'].common_sum_payments}</h6></td>
                                                <td><h6>${(item['sum'].planConstruct ? item['sum'].count_framework_payments / item['sum'].planConstruct * 100 : 0).toFixed(2)}%</h6></td>
                                                <td><h6>${(item['sum'].common_sum_payments ? item['sum'].common_sum_payments / item['sum'].planSum * 100 : 0).toFixed(2)}%</h6></td>
                                            </tr>`);

                                    $(`#dateOpen${branches[i].id}`).click(function (e) {
                                        e.preventDefault();
                                        if ($(`.dateInfo${branches[i].id}`).css('display') == 'none') {
                                            $(`.dateInfo${branches[i].id}`).fadeToggle(400, "linear", function () {
                                                $(`.dateInfo${branches[i].id}`).css("display", "table-row").css("transition-duration", "opacity 1s ease-out");
                                            });
                                        } else {
                                            $(`.dateInfo${branches[i].id}`).fadeToggle(400, "linear", function () {
                                                $(`.dateInfo${branches[i].id}`).css("display", "none").css("transition-duration", "opacity 1s ease-out");
                                            });
                                        }
                                    });

                                } else {
                                    $('.tableBranch').append(`
                        <tr class="bg-green showStat">
                            <td colspan="2"><h5>Итого:</h5></td>
                            <td><h6>${item.planConstruct}</h6></td>
                            <td><h6>${item.planSum}</h6></td>
                            <td><h6>${item.count_clients}</h6></td>
                            <td><h6>${item.leedAll}</h6></td>
                            <td><h6>${item.leedInWork}</h6></td>
                            <td><h6>${item.count_done_leeds}</h6></td>
                            <td><h6>${item.count_in_calls}</h6></td>
                            <td><h6>${item.count_out_calls}</h6></td>
                            <td><h6>${item.count_lost_calls}</h6></td>
                            <td><h6>${item.count_culations}</h6></td>
                            <td><h6>${item.common_culations}</h6></td>
                            <td><h6>${item.direct_sample}</h6></td>
                            <td><h6>${item.count_framework_culations}</h6></td>
                            <td><h6>${item.count_bills}</h6></td>
                            <td><h6>${item.count_framework_bills}</h6></td>
                            <td><h6>${item.common_sum_bills}</h6></td>
                            <td><h6>${item.count_payments}</h6></td>
                            <td><h6>${item.count_framework_payments}</h6></td>
                            <td><h6>${item.common_sum_payments}</h6></td>
                            <td><h6>${(item.planConstruct ? item.count_framework_payments / item.planConstruct * 100 : 0).toFixed(2)}%</h6></td>
                            <td><h6>${(item.planSum ? item.common_sum_payments / item.planSum * 100 : 0).toFixed(2)}%</h6></td>
                        </tr>
                        `);
                                }
                            });
                        });
                    }
                });
            })
        });
    </script>

@endsection