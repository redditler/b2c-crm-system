@extends('adminlte::page')

@section('content_header')
    <div class="headerNamePage">
        <h1>Dashboards</h1>
    </div>
@stop

@section('content')

    <div>
        <div class="filter_menu">
            <form id="leadDatePicker">
                <div class="filter_box">
                    <div class="form-group form-group-sm filter_leads_group flex block_filter">
                        <span>Дата</span>
                        <input type="date" name="leadDateFrom" id="leadDateFrom"
                               class="form-control input-sm"
                               value="{{!empty(request()->session()->get('period')) ? request()->session()->get('period')['leadDateFrom'] : \Carbon\Carbon::make(\App\Leed::dateFromLead()[0])->format('Y-m-d')}}">
                        <input type="date" name="leadDateTo" id="leadDateTo"
                               class="form-control input-sm"
                               value="{{!empty(request()->session()->get('period')) ? request()->session()->get('period')['leadDateTo'] : \Carbon\Carbon::make(\App\Leed::dateFromLead()[1])->format('Y-m-d')}}">
                    </div>
                    <div class="group_filter block_filter flex">
                    @if(\Illuminate\Support\Facades\Auth::user()->role_id == 1)
                        <span>Группа</span>
                        <label>
                            <select name="user_group_id[]" id="userGroup"
                                    class="multiselect-ui form-control form-control-sm">
                                    <option value="2" {{ isset(request()->session()->get('period')['userGroup']) ? (request()->session()->get('period')['userGroup'] == 2 ? 'selected' : '') : '' }}>Розница</option>
                                    <option value="1" {{ isset(request()->session()->get('period')['userGroup']) ? (request()->session()->get('period')['userGroup'] == 1 ? 'selected' : '') : '' }}>Онлайн</option>
                                    <option value="4" {{ isset(request()->session()->get('period')['userGroup']) ? (request()->session()->get('period')['userGroup'] == 4 ? 'selected' : '') : '' }}>DIY</option>
                            </select>
                        </label>
                    @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="dashboards-container">
            <div class="row" style="margin-bottom:500px;margin-top:15px;">
                <div class="col-md-4" style="min-height:422px;">
                    <div class="dashboard-short-block">
                        <h4 style="margin-bottom:15px;text-align:center;">Конверсия</h4>
                        <div class="dashboard-detailed" data-link="{{ route('Dashboards.detailedGraph', 'conversion') }}" data-spinner="false">Подробнее</div>
                        <canvas id="conversionSummary" height="250"></canvas>
                    </div>
                </div>
                <div class="col-md-4" style="min-height:422px;">
                    <div class="dashboard-short-block">
                        <h4 style="margin-bottom:15px;text-align:center;">Эффективность регионов</h4>
                        <div class="dashboard-detailed" data-link="{{ route('Dashboards.detailedGraph', 'conversion') }}" data-spinner="false">Подробнее</div>
                        <canvas id="conversionByRegions" height="250"></canvas>
                    </div>
                </div>
                <div class="col-md-4" style="min-height:422px;">
                    <div class="dashboard-short-block">
                        <h4 style="margin-bottom:15px;text-align:center;">ABC-квалификация</h4>
                        <canvas id="abcQualifiers" height="250"></canvas>
                        <div class="chart-description">С указанием категории: <strong id="qualifiedClients">0</strong><br>Без категории: <strong id="unqualifiedClients">0</strong></div>
                    </div>
                </div>
                    <div class="col-lg-12" style="height:20px;"></div>
                <div class="col-md-4" style="min-height:422px;">
                    <div class="dashboard-short-block">
                        <h4 style="margin-bottom:15px;text-align:center;">Источники клиентов</h4>
                        <canvas id="clientSources" height="250"></canvas>
                        <div class="chart-description">Всего с источниками: <strong id="clientsWithSource">0</strong><br>Без источников: <strong id="clientsWOSource">0</strong></div>
                    </div>
                </div>
                <div class="col-md-4" style="min-height:422px;">
                    <div class="dashboard-short-block">
                        <h4 style="margin-bottom:15px;text-align:center;">Пол клиентов</h4>
                        <canvas id="clientGenders" height="250"></canvas>
                        <div class="chart-description">С указанием пола: <strong id="clientsWithGender">0</strong><br>Без указания пола: <strong id="clientsWOGender">0</strong></div>
                    </div>
                </div>
                <div class="col-md-4" style="min-height:422px;">
                    <div class="dashboard-short-block">
                        <h4 style="margin-bottom:15px;text-align:center;">Возраст клиентов</h4>
                        <div class="dashboard-detailed" data-link="{{ route('Dashboards.detailedGraph', 'ages') }}" data-spinner="false">Подробнее</div>
                        <canvas id="clientAge" height="250"></canvas>
                        <div class="chart-description">С указанием возраста: <strong id="clientsWithAge">0</strong><br>Без указания возраста: <strong id="clientsWOAge">0</strong></div>
                    </div>
                </div>
                    <div class="col-lg-12" style="height:20px;"></div>
                <div class="col-md-4" style="min-height:422px;">
                    <div class="dashboard-short-block">
                        <h4 style="margin-bottom:15px;text-align:center;">Воронка продаж</h4>
                        <canvas id="leadsByStatus" height="250"></canvas>
                    </div>
                </div>
                <div class="col-md-4" style="min-height:422px;">
                    <div class="dashboard-short-block">
                        <h4 style="margin-bottom:15px;text-align:center;">Выполнение плана продаж</h4>
                        <canvas id="planProgress" height="250"></canvas>
                        <table style="position: absolute;width: 100%;left: 20px;bottom: 30px;font-size: 8pt;">
                            <tr>
                                <td style="width:110px;">∑ выполнение:</td>
                                <td style="width:170px;font-weight: bold;" id="planSucceedFrameworks">0</td>
                                <td style="font-weight: bold;" id="planSucceedSum">0</td>
                            </tr>
                            <tr>
                                <td>∑ план:</td>
                                <td style="font-weight: bold;" id="plannedFrameworks">0</td>
                                <td style="font-weight: bold;" id="plannedSum">0</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="telephonyBlock col-md-4" style="min-height:422px;">
                    <div class="dashboard-short-block">
                        <h4 style="margin-bottom:15px;text-align:center;">Использование телефонии</h4>
                        <div class="dashboard-detailed" data-link="{{ route('Dashboards.detailedTelephony') }}" data-spinner="true">Подробнее</div>
                        <canvas id="telephonyLines" height="250"></canvas>
                        <div class="chart-description">Входящих за период: <strong id="telUsageIncoming">0</strong><br>Исходящих за период: <strong id="telUsageOutgoing">0</strong></div>
                    </div>
                </div>
                    <div class="telephonyBlock col-lg-12" style="height:20px;"></div>
                <div class="col-md-4" style="min-height:422px;">
                    <div class="dashboard-short-block">
                        <h4 style="margin-bottom:15px;text-align:center;">Забракованные лиды</h4>
                        <div class="dashboard-detailed" data-link="{{ route('Dashboards.detailedGraph', 'rejected') }}" data-spinner="true">Подробнее</div>
                        <canvas id="rejectedLeads" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div id="pleaseWait" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width:auto;">
        <div class="modal-content" style="border-radius: 30px;">
            <div class="modal-body" style="padding: 30px;border-radius: 30px;background-color:#fff;">
                <img src="{{ asset('img/preloader.gif') }}" alt="">
                <div style="text-align: center;margin-top: 20px;">Сбор данных…</div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<style type="text/css">
    .dashboard-short-block {
        padding:15px 35px 35px 35px;
        background-color:#fff;
        width:100%;
        min-height:397px;
        -webkit-box-shadow: 3px 3px 23px -4px rgba(0,0,0,0.75);
        -moz-box-shadow: 3px 3px 23px -4px rgba(0,0,0,0.75);
        box-shadow: 3px 3px 23px -4px rgba(0,0,0,0.75);
        transition: 0.3s;
    }
        .dashboard-short-block:hover {
            -webkit-box-shadow: 3px 3px 35px 4px rgba(0,0,0,0.75);
            -moz-box-shadow: 3px 3px 35px 4px rgba(0,0,0,0.75);
            box-shadow: 3px 3px 35px 4px rgba(0,0,0,0.75);
        }
    .dashboard-detailed {
        position: absolute;
        bottom: 12px;
        right: 0;
        background-color: #ececec;
        padding: 10px;
        border: 1px solid #8c8484;
        border-radius: 50px;
        color: #4e4e4e;
        cursor: pointer;
    }
    .chart-description {
        position: absolute;
        left: 22px;
        bottom: 30px;
        font-size: 8pt;
    }
    .modal {
      text-align: center;
      padding: 0!important;
    }

    .modal:before {
      content: '';
      display: inline-block;
      height: 100%;
      vertical-align: middle;
      margin-right: -4px;
    }

    .modal-dialog {
      display: inline-block;
      text-align: left;
      vertical-align: middle;
    }
</style>
@endsection

@section('tmp_js')
    <script src="/js/chartjs-labels.js"></script>
    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        $('.multiselect-ui').multiselect({
            includeSelectAllOption: false,
            enableFiltering: false,
            defaultChecked: true,
            nonSelectedText: 'Выбрать',
            selectAllText: 'Все',
            allSelectedText: 'Все'
        });

        $('.dashboard-detailed').click(function(){
            if($(this).attr('data-spinner') == "true"){
                $('#pleaseWait').modal('show');
            }
            window.location.href = $(this).attr('data-link');
        });

        let init = {
            id: [],
            all: [],
            done: [],
            names: [],
            coleurs: [],
            conversions: [],
            summaryConv: 0,
            summaryConvDone: 0,
            ajaxSetPeriod: function() {

                $('#pleaseWait').modal('show');

                if(($('#userGroup').val() == 4) || ({{ \Illuminate\Support\Facades\Auth::user()->group_id }} == 4)){
                    $('.telephonyBlock').fadeOut();
                }else{
                    $('.telephonyBlock').fadeIn();
                }

                $.ajax({
                    method: 'post',
                    url: '{{ route('Dashboards.init') }}',
                    data: {
                        leadDateFrom: $('#leadDateFrom').val(),
                        leadDateTo: $('#leadDateTo').val(),
                        userGroup: $('#userGroup').val(),
                        buildType: 'all'
                    },
                    success: function(data) {

                        init.id = [];                        init.conversions = [];                  init.names = [];
                        init.done = [];                      init.all = [];                          init.coleurs = [];
                        init.summaryConv = 0;                init.summaryConvDone = 0;

                        /*
                            Обнуление общей статистики, иначе она суммируется
                        */
                        $('#qualifiedClients').text(0);                     $('#unqualifiedClients').text(0);
                        $('#clientsWithSource').text(0);                    $('#clientsWOSource').text(0);
                        $('#clientsWithGender').text(0);                    $('#clientsWOGender').text(0);
                        $('#clientsWithAge').text(0);                       $('#clientsWOAge').text(0);

                        /*
                            Построение диаграмм
                        */
                        buildSourcesChart(data);                            buildRegionalChart(data);
                        buildQualifiersChart(data);                         buildGendersChart(data);
                        buildAgesCharts(data);                              buildLeadsChart(data);
                        buildConversionChart(data);                         buildTelephonyChart(data.telephony);
                        buildPlanProcessChart(data.plan);                   buildRejectedChart(data);

                        $('#pleaseWait').modal('hide');

                    }
                });
            }
        };

        let doneData = {
            label: 'Оплачено',
            data: [],
            borderWidth: 2,
            hoverBorderWidth: 0,
            backgroundColor: 'rgba(13,153,89,0.6)'
        };
        let allData = {
            label: 'Не оплачено',
            data: [],
            borderWidth: 2,
            hoverBorderWidth: 0,
            backgroundColor: 'rgba(17,120,153, .6)'
        };
        let chartOptions = {
            scales: {
                yAxes: [{
                    stacked: true,
                    ticks: {
                        display: false
                    }
                }],
                xAxes: [{
                    stacked: true,
                    ticks: {
                        display: false
                    }
                }]
            },
            elements: {
                rectangle: {
                    borderSkipped: 'left',
                }
            },
            plugins: {
                labels: {
                    minDisplayValue: 65536
                }
            }
        };
        var ctx = document.getElementById('conversionSummary').getContext('2d');
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [doneData, allData],
            },
            options: chartOptions
        });

        var ctxPlan = document.getElementById('planProgress').getContext('2d');
        var planProgressChart = new Chart(ctxPlan, {
            type: 'bar',
            data: {
                datasets: [
                    {
                        label: 'Выполнение',
                        backgroundColor: 'rgba(13,153,89,0.6)',
                        borderWidth: 2,
                        hoverBorderWidth: 0,
                        data: []
                    }, 
                    {
                        label: 'План',
                        backgroundColor: 'rgba(17,120,153, .6)',
                        borderWidth: 2,
                        hoverBorderWidth: 0,
                        data: []
                    }
                ],
            },
            options: chartOptions
        });

        /*
            Вывод диаграммы ABC-квалификации
        */
        var ctxABC = document.getElementById('abcQualifiers').getContext('2d');
        var abcQualifiersPie = new Chart(
            ctxABC,
            {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [
                            init.abc1,
                            init.abc2,
                            init.abc3
                        ],
                        backgroundColor: [
                            'rgba(13,153,89,0.6)',
                            'rgba(17,120,153,0.6)',
                            'rgba(46,197,120,0.6)'
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: [
                        'A', 'B', 'C'
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        labels: {
                            render: 'percentage',
                            minDisplayValue: 10,
                            textShadow: true,
                            shadowColor: 'rgba(22, 22, 22, 0.75)',
                            shadowOffsetX: 0,
                            shadowOffsetY: 0,
                            shadowBlur: 10,
                            fontSize: 14,
                            fontColor: 'white',
                            addNumValue: true
                        }
                    }
                }
            }
        );

        /*
            Вывод диаграммы по источникам клиентов
        */
        var ctxSrc = document.getElementById('clientSources').getContext('2d');
        var clientSourcesPie = new Chart(
            ctxSrc,
            {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [],
                        backgroundColor: [],
                        label: 'Dataset 1'
                    }],
                    labels: []
                },
                options: {
                    responsive: true,
                    legend : {
                        fullWidth: true,
                        labels: {
                            fontSize: 10,
                            boxWidth: 10
                        }
                    },
                    plugins: {
                        labels: {
                            render: 'percentage',
                            minDisplayValue: 10,
                            textShadow: true,
                            shadowColor: 'rgba(22, 22, 22, 0.75)',
                            shadowOffsetX: 0,
                            shadowOffsetY: 0,
                            shadowBlur: 10,
                            fontSize: 14,
                            fontColor: 'white',
                            addNumValue: true
                        }
                    }
                }
            }
        );

        /*
            Вывод диаграммы по половой принадлежности клиентов
        */
        var ctxGnd = document.getElementById('clientGenders').getContext('2d');
        var clientGendersPie = new Chart(
            ctxGnd,
            {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [ 0, 0 ],
                        backgroundColor: [
                            'rgba(13,153,89,0.6)',
                            'rgba(17,120,153,0.6)'
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: [
                        'Мужской',
                        'Женский'
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        labels: {
                            render: 'percentage',
                            minDisplayValue: 10,
                            textShadow: true,
                            shadowColor: 'rgba(22, 22, 22, 0.75)',
                            shadowOffsetX: 0,
                            shadowOffsetY: 0,
                            shadowBlur: 10,
                            fontSize: 14,
                            fontColor: 'white',
                            addNumValue: true
                        }
                    }
                }
            }
        );

        /*
            Вывод диаграммы по возрастной структуре клиентов
        */
        var ctxAge = document.getElementById('clientAge').getContext('2d');
        var clientAgesPie = new Chart(
            ctxAge,
            {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [],
                        backgroundColor: [],
                        label: 'Dataset 1'
                    }],
                    labels: []
                },
                options: {
                    responsive: true,
                    plugins: {
                        labels: {
                            render: 'percentage',
                            minDisplayValue: 10,
                            textShadow: true,
                            shadowColor: 'rgba(22, 22, 22, 0.75)',
                            shadowOffsetX: 0,
                            shadowOffsetY: 0,
                            shadowBlur: 10,
                            fontSize: 14,
                            fontColor: 'white',
                            addNumValue: true
                        }
                    }
                }
            }
        );

        /*
            Вывод малой диграммы конверсии по регионам
        */
        var ctxCR = document.getElementById('conversionByRegions').getContext('2d');
        var conversionRegionsPie = new Chart(
            ctxCR,
            {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [],
                        backgroundColor: []
                    }],
                    labels: []
                },
                options: {
                    responsive: true,
                    legend : {
                        fullWidth: true,
                        labels: {
                            fontSize: 10,
                            boxWidth: 10
                        }
                    },
                    plugins: {
                        labels: {
                            render: 'percentage',
                            minDisplayValue: 5,
                            textShadow: true,
                            shadowColor: 'rgba(22, 22, 22, 0.75)',
                            shadowOffsetX: 0,
                            shadowOffsetY: 0,
                            shadowBlur: 10,
                            fontSize: 14,
                            fontColor: 'white',
                            addNumValue: true
                        }
                    }
                }
            }
        );

        /*
            Вывод воронки продаж
        */
        var ctxLeads = document.getElementById('leadsByStatus').getContext('2d');
        var leadsByStatusPie = new Chart(
            ctxLeads,
            {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            'rgba(172, 231, 249, 0.75)',
                            'rgba(1, 210, 240, 0.75)',
                            'rgba(57, 72, 95, 0.75)',
                            'rgba(31, 44, 78, 0.75)',
                            'rgba(253, 111, 117, 0.75)',
                            'rgba(255, 144, 153, 0.75)'
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: ['Новый', 'Обработка', 'Замер', 'Предложение', 'Выставлен счет', 'Оплачен']
                },
                options: {
                    responsive: true,
                    legend : {
                        fullWidth: true,
                        labels: {
                            fontSize: 10,
                            boxWidth: 10
                        }
                    },
                    plugins: {
                        labels: {
                            render: 'percentage',
                            minDisplayValue: 10,
                            textShadow: true,
                            shadowColor: 'rgba(22, 22, 22, 0.75)',
                            shadowOffsetX: 0,
                            shadowOffsetY: 0,
                            shadowBlur: 10,
                            fontSize: 14,
                            fontColor: 'white',
                            addNumValue: true
                        }
                    }
                }
            }
        );

        /*
            Вывод воронки продаж
        */
        var ctxLeads = document.getElementById('leadsByStatus').getContext('2d');
        var leadsByStatusPie = new Chart(
            ctxLeads,
            {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            'rgba(172, 231, 249, 0.75)',
                            'rgba(1, 210, 240, 0.75)',
                            'rgba(57, 72, 95, 0.75)',
                            'rgba(31, 44, 78, 0.75)',
                            'rgba(253, 111, 117, 0.75)',
                            'rgba(255, 144, 153, 0.75)'
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: ['Новый', 'Обработка', 'Замер', 'Предложение', 'Выставлен счет', 'Оплачен']
                },
                options: {
                    responsive: true,
                    legend : {
                        fullWidth: true,
                        labels: {
                            fontSize: 10,
                            boxWidth: 10
                        }
                    },
                    plugins: {
                        labels: {
                            render: 'percentage',
                            minDisplayValue: 10,
                            textShadow: true,
                            shadowColor: 'rgba(22, 22, 22, 0.75)',
                            shadowOffsetX: 0,
                            shadowOffsetY: 0,
                            shadowBlur: 10,
                            fontSize: 14,
                            fontColor: 'white',
                            addNumValue: true
                        }
                    }
                }
            }
        );

        /*
            Вывод диаграммы телефонии
        */
        var ctxTel = document.getElementById('telephonyLines').getContext('2d');
        var telephonyLines = new Chart(
            ctxTel, 
            {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Входящие',
                        fill: false,
                        yAxisID: 'y-axis-1',
                        backgroundColor: 'rgba(67, 184, 61, 0.6)',
                        borderColor: 'rgba(67, 184, 61, 0.6)',
                        data: []
                    }, {
                        label: 'Исходящие',
                        fill: false,
                        yAxisID: 'y-axis-1',
                        backgroundColor: 'rgba(61, 122, 184, 0.6)',
                        borderColor: 'rgba(61, 122, 184, 0.6)',
                        data: []
                    }]
                },
                options: {
                    responsive: true,
                    hoverMode: 'index',
                    stacked: true,
                    title: {
                        display: false,
                        text: 'Chart.js Line Chart - Multi Axis'
                    },
                    scales: {
                        yAxes: 
                        [
                            {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                id: 'y-axis-1'
                            }
                        ],
                        xAxes:
                        [
                            {
                                display: false
                            }
                        ]
                    }
                }
            }
        );

        /*
            Вывод воронки продаж
        */
        var ctxRejected = document.getElementById('rejectedLeads').getContext('2d');
        var rejectedLeadsPie = new Chart(
            ctxRejected,
            {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            'rgba(172, 231, 249, 0.75)',
                            'rgba(1, 210, 240, 0.75)',
                            'rgba(57, 72, 95, 0.75)'
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: ['Потенциальные', 'Оплаченные', 'Забракованные']
                },
                options: {
                    responsive: true,
                    legend : {
                        fullWidth: true,
                        labels: {
                            fontSize: 10,
                            boxWidth: 10
                        }
                    },
                    plugins: {
                        labels: {
                            render: 'percentage',
                            minDisplayValue: 10,
                            textShadow: true,
                            shadowColor: 'rgba(22, 22, 22, 0.75)',
                            shadowOffsetX: 0,
                            shadowOffsetY: 0,
                            shadowBlur: 10,
                            fontSize: 14,
                            fontColor: 'white',
                            addNumValue: true
                        }
                    }
                }
            }
        );

        /*
            Построение и обновление забракованных лидов
        */
        async function buildRejectedChart(data) {
            arrStat = [
                (parseInt(data.leedstat[0].status_5)+parseInt(data.leedstat[0].status_11)+parseInt(data.leedstat[0].status_12)+parseInt(data.leedstat[0].status_13)+parseInt(data.leedstat[0].status_14)),
                data.leedstat[0].status_15,
                data.leedstat[0].rejected_count
            ];
            rejectedLeadsPie.data.datasets[0].data = arrStat;
            rejectedLeadsPie.update();
        }

        /*
            Построение и обновление воронки продаж
        */
        async function buildLeadsChart(data) {
            arrStat = [
                data.leedstat[0].status_5,              data.leedstat[0].status_11, 
                data.leedstat[0].status_12,             data.leedstat[0].status_13, 
                data.leedstat[0].status_14,             data.leedstat[0].status_15
            ];
            leadsByStatusPie.data.datasets[0].data = arrStat;
            leadsByStatusPie.update();
        };

        async function buildConversionChart(data){
            Object.keys(data.branches).forEach(function(item) {
                init.conversions.push(data.branches[item].conversion);                  init.names.push(data.branches[item].name);
                init.all.push(data.branches[item].all-data.branches[item].done);        init.done.push(data.branches[item].done);
                init.id.push(data.branches[item].id);                                   init.summaryConv += data.branches[item].all-data.branches[item].done;
                init.summaryConvDone += data.branches[item].done;
            });

            allData.data = init.all;
            doneData.data = init.done;
            myBarChart.data.labels = init.names;
            myBarChart.update();
        }

        async function buildAgesCharts(data){
            init.cagesLabels = [ '<20', '20-30', '30-45', '45-60', '>60' ];
            init.cagesData = [ 0, 0, 0, 0, 0 ];
            init.cagesColeurs = []
            data.ages.forEach(function(item){
                if($.isNumeric(item.age)){
                    if(item.age < 20){
                        init.cagesData[0] += item.count;
                    }else if((item.age >= 20) && (item.age < 30)){
                        init.cagesData[1] += item.count;
                    }else if((item.age >= 30) && (item.age < 45)){
                        init.cagesData[2] += item.count;
                    }else if((item.age >= 45) && (item.age < 60)){
                        init.cagesData[3] += item.count;
                    }else if(item.age >= 60){
                        init.cagesData[4] += item.count;
                    }
                    $('#clientsWithAge').text(parseInt($('#clientsWithAge').text()) + item.count);
                }else{
                    $('#clientsWOAge').text(parseInt($('#clientsWOAge').text()) + item.count);
                }
            });
            clientAgesPie.data.labels = init.cagesLabels;
            clientAgesPie.data.datasets[0].data = init.cagesData;
            init.cagesLabels.forEach(function(inner){
                init.cagesColeurs.push('rgb(' + Math.floor((Math.random() * 256)) + ',' + Math.floor((Math.random() * 256)) + ',' + Math.floor((Math.random() * 256)) + ',0.6)');
            });
            clientAgesPie.data.datasets[0].backgroundColor = init.cagesColeurs;
            clientAgesPie.update();
        }

        async function buildGendersChart(data){
            init.cgenderM = 0;
            init.cgenderF = 0;
            data.genders.forEach(function(item){
                if((item.gender == 1) || (item.gender == 0)){
                    if(item.gender == 0){
                        init.cgenderF = item.count;
                    }else if(item.gender == 1){
                        init.cgenderM = item.count;
                    }
                    $('#clientsWithGender').text(parseInt($('#clientsWithGender').text()) + item.count);
                }else{
                    $('#clientsWOGender').text(parseInt($('#clientsWOGender').text()) + item.count);
                }
            });
            clientGendersPie.data.datasets[0].data = [ init.cgenderF, init.cgenderM ];
            clientGendersPie.update();
        }

        async function buildQualifiersChart(data){
            init.abc1 = 0;
            init.abc2 = 0;
            init.abc3 = 0;
            data.qualifiers.forEach(function(item){
                if(item.contact_quality_id>0){
                    if(item.contact_quality_id == 1){
                        init.abc1 = item.count;
                    }else if(item.contact_quality_id == 2){
                        init.abc2 = item.count;
                    }else if(item.contact_quality_id == 3){
                        init.abc3 = item.count;
                    }
                    $('#qualifiedClients').text(parseInt($('#qualifiedClients').text()) + item.count);
                }else{
                    $('#unqualifiedClients').text(parseInt($('#unqualifiedClients').text()) + item.count);
                }
            });
            abcQualifiersPie.data.datasets[0].data = [ init.abc1, init.abc2, init.abc3 ];
            abcQualifiersPie.update();
        }

        async function buildRegionalChart(data){
            init.rmDetailsLabels = [];
            init.rmDetailsCount = [];
            Object.keys(data.rmdetails.resolver).forEach(function(item){
                init.rmDetailsLabels.push(data.rmdetails.resolver[item]);
            });
            Object.keys(data.rmdetails.count).forEach(function(item){
                init.rmDetailsCount.push(data.rmdetails.count[item]);
            });

            conversionRegionsPie.data.datasets[0].data = init.rmDetailsCount;
            conversionRegionsPie.data.labels = init.rmDetailsLabels;
            init.rmDetailsLabels.forEach(function(inner){
                init.coleurs.push('rgb(' + Math.floor((Math.random() * 256)) + ',' + Math.floor((Math.random() * 256)) + ',' + Math.floor((Math.random() * 256)) + ',0.6)');
            });
            conversionRegionsPie.data.datasets[0].backgroundColor = init.coleurs;
            conversionRegionsPie.update();
        }

        async function buildSourcesChart(data){
            init.csourcesLabels = [];
            init.csourcesData = [];
            init.csourcesColeurs = [];
            data.sources.forEach(function(item){
                if(item.sources_id != 0){
                    init.csourcesLabels.push(item.sources_id);
                    init.csourcesData.push(item.count);
                    $('#clientsWithSource').text(parseInt($('#clientsWithSource').text()) + item.count);
                }else{
                    $('#clientsWOSource').text(parseInt($('#clientsWOSource').text()) + item.count);
                }
            });

            clientSourcesPie.data.labels = init.csourcesLabels;
            clientSourcesPie.data.datasets[0].data = init.csourcesData;
            init.csourcesLabels.forEach(function(inner){
                init.csourcesColeurs.push('rgb(' + Math.floor((Math.random() * 256)) + ',' + Math.floor((Math.random() * 256)) + ',' + Math.floor((Math.random() * 256)) + ',0.6)');
            });
            clientSourcesPie.data.datasets[0].backgroundColor = init.csourcesColeurs;
            clientSourcesPie.update();
        }

        async function buildTelephonyChart(data){
            var tel = {
                labels: [],
                incoming: [],
                outgoing: [],
                in_sum: 0,
                out_sum: 0
            };
            Object.keys(data).forEach(function(item){
                if((data[item].incoming>0) || (data[item].outgoing>0)){
                    tel.labels.push(item);
                    tel.incoming.push(parseInt(data[item].incoming));
                    tel.in_sum += parseInt(data[item].incoming);
                    tel.outgoing.push(parseInt(data[item].outgoing));
                    tel.out_sum += parseInt(data[item].outgoing);
                }
            });
            $('#telUsageIncoming').text(tel.in_sum);
            $('#telUsageOutgoing').text(tel.out_sum);
            telephonyLines.data.labels = tel.labels;
            telephonyLines.data.datasets[0].data = tel.incoming;
            telephonyLines.data.datasets[1].data = tel.outgoing;
            telephonyLines.update();
        }

        async function buildPlanProcessChart(data){
            $('#planSucceedFrameworks').text(data.succeed.frameworks + ' шт.');      $('#planSucceedSum').text(data.succeed.sum + '₽');
            $('#plannedFrameworks').text(data.planned.frameworks + ' шт.');          $('#plannedSum').text(data.planned.sum + '₽');
            planProgressChart.data.labels = ['Конструкции', 'Сумма'];
            planProgressChart.data.datasets[0].data = [ data.progress.frameworks, data.progress.sum ];
            planProgressChart.data.datasets[1].data = [ 
                (100-data.progress.frameworks).toFixed(2), 
                (100-data.progress.sum).toFixed(2)
            ];
            planProgressChart.update();
        }

        /*
            Запуск формирования диаграммы и ее перестроение при изменеии параметров
        */
        $(document).ready(function(){

            var start = false;
            function startScript() {
                if(!start)
                    init.ajaxSetPeriod();
                start = true;
            }
            startScript();

            $('#leadDateFrom, #leadDateTo, #userGroup').change(function () {
                init.ajaxSetPeriod();
            });

        });

    </script>
@endsection
