@extends('adminlte::page')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.1/build/grids-min.css">

    <section class="content__wrapper title-style" data-id="manager-static">
        <div class="container statistic">
            <div class="container__title">
                <h1 class="title">Статистика</h1>
            </div>
            <div class="container__content">

                <div class="statistic__table ">
                    <div class="table__header pure-g">

                        <div class="header__column single pure-u-2-24">
                            <span class="column__content">Дата</sapn>
                        </div>

                        <div class="header__column single pure-u-2-24">
                            <span class="column__content">Посетители</sapn>
                        </div>

                        <div class="header__column multiple-3 pure-u-7-24">
                            <div class="column__header">
                                <span class="column__content">Лиды Steko</sapn>
                            </div>
                            <div class="column__footer pure-g">
                                <div class="footer__column pure-u-1-3"><span class="column__content blue-bg">Всего</sapn></div>
                                <div class="footer__column pure-u-1-3"><span class="column__content">В работе</sapn></div>
                                <div class="footer__column pure-u-1-3"><span class="column__content ">Отработано</sapn></div>
                            </div>
                        </div>

                        <div class="header__column multiple-2 pure-u-6-24">
                            <div class="column__header">
                                <span class="column__content">Звонки</sapn>
                            </div>
                            <div class="column__footer pure-g">
                                <div class="footer__column pure-u-1-2"><span class="column__content">Входящие</sapn></div>
                                <div class="footer__column pure-u-1-2"><span class="column__content">Исходящие</sapn></div>
                            </div>
                        </div>

                        <div class="header__column multiple-3 pure-u-7-24">
                            <div class="column__header">
                                <span class="column__content">Оплата</sapn>
                            </div>
                            <div class="column__footer pure-g">
                                <div class="footer__column pure-u-1-3"><span class="column__content">Кол-во оплат</sapn></div>
                                <div class="footer__column pure-u-1-3"><span class="column__content">Кол-во конструк.</sapn></div>
                                <div class="footer__column pure-u-1-3"><span class="column__content blue-bg">Сумма</sapn></div>
                            </div>
                        </div>

                    </div>
                    <div class="table__body">
                        @for($i = 1; $i <= $managerStatic['period']; $i++)
                        <div class="body__row pure-g">
                            <div class="body__column pure-u-2-24"><span class="column__content tableData ">{{\Carbon\Carbon::make($i.'-'.\Carbon\Carbon::now()->format('m-Y'))->format('d-m-Y')}}</span></div>
                            <div class="body__column pure-u-2-24"><span class="column__content tableCountClients">{{empty($managerStatic['reportDay'][$i]) ? '0' : $managerStatic['reportDay'][$i]->count_clients}}</span></div>
                            <div class="body__column pure-u-7-24">
                                <div class="column pure-g">
                                    <div class="multiple__content pure-u-1-3"><span class="column__content tableLeed blue-bg">{{empty($managerStatic['leeDay'][$i]) ? '0' :count($managerStatic['leeDay'][$i])}}</span></div>
                                    <div class="multiple__content pure-u-1-3"><span class="column__content tableLeedUser">{{empty($managerStatic['leedUser'][$i]) ? '0' :count($managerStatic['leedUser'][$i])}}</span></div>
                                    <div class="multiple__content pure-u-1-3"><span class="column__content tableLeedProcessed ">{{empty($managerStatic['reportDay'][$i]) ? '0' : $managerStatic['reportDay'][$i]->count_done_leeds}}</span></div>
                                </div>
                            </div>
                            <div class="body__column pure-u-6-24">
                                <div class="column pure-g">
                                    <div class="multiple__content pure-u-1-2"><span class="column__content tableCountInCalls">{{empty($managerStatic['reportDay'][$i]) ? '0' : $managerStatic['reportDay'][$i]->count_in_calls}}</span></div>
                                    <div class="multiple__content pure-u-1-2"><span class="column__content tableCountOutCalls">{{empty($managerStatic['reportDay'][$i]) ? '0' : $managerStatic['reportDay'][$i]->count_out_calls}}</span></div>
                                </div>
                            </div>
                            <div class="body__column pure-u-7-24">
                                <div class="column pure-g">
                                    <div class="multiple__content pure-u-1-3"><span class="column__content tableCountPayments">{{empty($managerStatic['reportDay'][$i]) ? '0' : $managerStatic['reportDay'][$i]->count_payments}}</span></div>
                                    <div class="multiple__content pure-u-1-3"><span class="column__content tableCountFrameworkPayments">{{empty($managerStatic['reportDay'][$i]) ? '0' : $managerStatic['reportDay'][$i]->count_framework_payments}}</span></div>
                                    <div class="multiple__content pure-u-1-3"><span class="column__content tableCommonSumPayments blue-bg">{{empty($managerStatic['reportDay'][$i]) ? '0' : $managerStatic['reportDay'][$i]->common_sum_payments}}</span></div>
                                </div>
                            </div>
                        </div>
                        @endfor
                        <div class="body__row pure-g" id="total"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('users_js')
    <script type="text/javascript">
        
        $(document).ready(function () {
            var t = Number("{{\Carbon\Carbon::now()->format('t')}}");
            function totalTable(z) {
                var result = 0;
                for (var i = 0; i < "{{$managerStatic['period']}}"; i++) {
                    result += Number($(z)[i].textContent);
                }
                return result;
            }

            var colummnTable = {
                total: "Итого:",
                countClients: totalTable($('.tableCountClients')),
                //LEED
                leeDay: totalTable($('.tableLeed')),
                leedUser: totalTable($('.tableLeedUser')),
                leedProcessed: totalTable($('.tableLeedProcessed')),
                //Calls
                countInCalls: totalTable($('.tableCountInCalls')),
                countOutCalls: totalTable($('.tableCountOutCalls')),
                //PAYMENT
                countPayments: totalTable($('.tableCountPayments')),
                countFrameworkPayments: totalTable($('.tableCountFrameworkPayments')),
                commonSumPayments: totalTable($('.tableCommonSumPayments'))
            };
            var totalTable =
                        ` <div class="body__column pure-u-2-24"><span class="column__content tableData">${colummnTable.total}</span></div>
                            <div class="body__column pure-u-2-24"><span class="column__content tableCountClients">${colummnTable.countClients}</span></div>
                            <div class="body__column pure-u-7-24">
                                <div class="column pure-g">
                                    <div class="multiple__content pure-u-1-3"><span class="column__content tableLeed blue-bg">${colummnTable.leeDay}</span></div>
                                    <div class="multiple__content pure-u-1-3"><span class="column__content tableLeedUser">${colummnTable.leedUser}</span></div>
                                    <div class="multiple__content pure-u-1-3"><span class="column__content tableLeedProcessed ">${colummnTable.leedProcessed }</span></div>
                                </div>
                            </div>
                            <div class="body__column pure-u-6-24">
                                <div class="column pure-g">
                                    <div class="multiple__content pure-u-1-2"><span class="column__content tableCountInCalls">${colummnTable.countInCalls}</span></div>
                                    <div class="multiple__content pure-u-1-2"><span class="column__content tableCountOutCalls">${colummnTable.countOutCalls}</span></div>
                                </div>
                            </div>
                            <div class="body__column pure-u-7-24">
                                <div class="column pure-g">
                                    <div class="multiple__content pure-u-1-3"><span class="column__content tableCountPayments">${colummnTable.countPayments}</span></div>
                                    <div class="multiple__content pure-u-1-3"><span class="column__content tableCountFrameworkPayments">${colummnTable.countFrameworkPayments}</span></div>
                                    <div class="multiple__content pure-u-1-3"><span class="column__content tableCommonSumPayments blue-bg">${colummnTable.commonSumPayments}</span></div>
                                </div>
                            </div>`;

            $('#total').html(totalTable);
        });
    </script>
@endsection