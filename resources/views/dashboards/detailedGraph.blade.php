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
                        <span>Сортировка</span>
                        <label>
                            <select name="report_sort" id="sortType"
                                    class="multiselect-ui form-control form-control-sm">
                    @if($detailsType == "conversion")
                                    <option value="summary">Сумма лидов</option>
                                    <option value="paid">Оплаченные лиды</option>
                                    <option value="conversion">Конверсия</option>
                    @elseif($detailsType == "ages")
                                    <option value="summary">По количеству</option>
                                    <option value="ages">По возрасту</option>
                    @elseif($detailsType == "rejected")
                                    <option value="bounce">По забракованным</option>
                                    <option value="main">По потенциальным</option>
                                    <option value="paid">По оплаченным</option>
                    @endif
                            </select>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        <div class="dashboards-container">
            <div class="row" style="margin-bottom:500px;margin-top:15px;">
                <div class="col-sm-12" style="min-height:422px;">
                    <div class="dashboard-short-block">
    @if($detailsType == "conversion")
                        <h4 style="margin-bottom:15px;text-align:center;">Конверсия</h4>
    @elseif($detailsType == "ages")
                        <h4 style="margin-bottom:15px;text-align:center;">Возрастная структура клиентской базы</h4>
    @elseif($detailsType == "rejected")
                        <h4 style="margin-bottom:15px;text-align:center;">Забракованные лиды</h4>
    @endif
                        <canvas id="SalonChart" height="200" style="background-color:#fff;"></canvas>
                    </div>
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
        -webkit-box-shadow: 3px 3px 23px -4px rgba(0,0,0,0.75);
        -moz-box-shadow: 3px 3px 23px -4px rgba(0,0,0,0.75);
        box-shadow: 3px 3px 23px -4px rgba(0,0,0,0.75);
        transition: 0.3s;
    }
</style>
@endsection

@section('tmp_js')
<script type="text/javascript" src="{{ asset('js/bootstrap-multiselect.js ') }}"></script>
    <script>

        $('.multiselect-ui').multiselect({
            includeSelectAllOption: false,
            enableFiltering: false,
            defaultChecked: true,
            nonSelectedText: 'Выбрать',
            selectAllText: 'Все',
            allSelectedText: 'Все'
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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: 'post',
                    url: '{{ route('Dashboards.init') }}',
                    data: {
                        leadDateFrom:   $('#leadDateFrom').val(),
                        leadDateTo:     $('#leadDateTo').val(),
                        userGroup:      $('#userGroup').val(),
                        buildType:      '{{ $detailsType }}',
                        sortType:       $('#sortType').val()
                    },
                    success: function(data) {

    @if($detailsType == "conversion")

                        init.id = [];
                        init.conversions = [];
                        init.names = [];
                        init.done = [];
                        init.all = [];

                        data.branches.forEach(function(item) {
                            init.conversions.push(item.conversion);
                            init.names.push(item.name);
                            init.all.push(item.all-item.done);
                            init.done.push(item.done);
                            init.id.push(item.id);
                            init.summaryConv += item.all-item.done;
                            init.summaryConvDone += item.done;
                        });

                        allData.data = init.all;
                        doneData.data = init.done;
                        myBarChart.data.labels = init.names;
                        myBarChart.update();

    @elseif($detailsType == "ages")

                        init.ages = [];
                        init.count = [];

                        data.ages.forEach(function(item){
                            if(item.age>0){
                                init.ages.push(item.age);
                                init.count.push(item.count);
                            }
                        });

                        allData.data = init.count;
                        myBarChart.data.labels = init.ages;
                        myBarChart.update();
    @elseif($detailsType == "rejected")

                        init.id = [];
                        init.labels = [];
                        init.all = [];
                        init.paid = [];
                        init.rejected = [];

                        data.rejected.forEach(function(item){
                            init.id.push(item.id);
                            init.labels.push(item.name);
                            init.all.push(item.all);
                            init.paid.push(item.done);
                            init.rejected.push(item.rejected);
                        });

                        allData.data = init.all;
                        doneData.data = init.paid;
                        rejectedData.data = init.rejected;

                        myBarChart.data.labels = init.labels;
                        myBarChart.update();

    @endif

                    }
                });
            }
        };

    @if($detailsType == "conversion")

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
                    barPercentage: 1.2,
                    stacked: true
                }],
                xAxes: [{
                    stacked: true
                }],
            },
            animation: {
                duration: 0,
                onProgress: function () {
                    var flag = 0;
                    var ctx = this.chart.ctx;
                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'normal', Chart.defaults.global.defaultFontFamily);
                    ctx.fillStyle = 'rgba(194, 114, 118, 1)';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    this.data.datasets.forEach(function (dataset) {
                        for (var i = 0; i < dataset.data.length; i++) {
                            var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model;
                            if(flag > 0) {
                                ctx.fillText(init.conversions[i] + '%; Σ: ' + (init.all[i]+init.done[i]), model.x + 45, model.y + 7);
                            }
                        }
                        flag++;
                    });
                },
                onComplete: function () {
                    var flag = 0;
                    var ctx = this.chart.ctx;
                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'normal', Chart.defaults.global.defaultFontFamily);
                    ctx.fillStyle = 'rgba(194, 114, 118, 1)';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    this.data.datasets.forEach(function (dataset) {
                        for (var i = 0; i < dataset.data.length; i++) {
                            var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model;
                            if(flag > 0) {
                                ctx.fillText(init.conversions[i] + '%; Σ: ' + (init.all[i]+init.done[i]), model.x + 45, model.y + 7);
                            }
                        }
                        flag++;
                    });
                }
            },
            elements: {
                rectangle: {
                    borderSkipped: 'left',
                }
            },
            onClick: function(evt) {
                @if(\Illuminate\Support\Facades\Auth::user()->role_id != 3)
                if($('#userGroup').val() != 1){
                    var activePoints = myBarChart.getElementAtEvent(evt);
                    if(typeof activePoints[0] !== 'undefined') {
                        window.location.href = "/dashboards/details/conversion/"+init.id[activePoints[0]._index];
                    }
                }
                @endif
            }
        };

        /*
            Вывод основной диаграммы конверсии
        */
        var ctx = document.getElementById('SalonChart').getContext('2d');
        var myBarChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: [],
            datasets: [doneData, allData],
        },
        options: chartOptions
        });

    @elseif($detailsType == "rejected")

        let doneData = {
            label: 'Оплачено',
            data: [],
            borderWidth: 2,
            hoverBorderWidth: 0,
            backgroundColor: 'rgba(13,153,89,0.6)'
        };
        let allData = {
            label: 'Потенциальные',
            data: [],
            borderWidth: 2,
            hoverBorderWidth: 0,
            backgroundColor: 'rgba(17,120,153, .6)'
        };
        let rejectedData = {
            label: 'Забракованные',
            data: [],
            borderWidth: 2,
            hoverBorderWidth: 0,
            backgroundColor: 'rgba(212, 93, 93, 1)'
        };

        let chartOptions = {
            scales: {
                yAxes: [{
                    barPercentage: 1.2,
                    stacked: true
                }],
                xAxes: [{
                    stacked: true
                }],
            },
            elements: {
                rectangle: {
                    borderSkipped: 'left',
                }
            },
            onClick: function(evt) {
                @if(\Illuminate\Support\Facades\Auth::user()->role_id != 3)
                if($('#userGroup').val() != 1){
                    var activePoints = myBarChart.getElementAtEvent(evt);
                    if(typeof activePoints[0] !== 'undefined') {
                        window.location.href = "/dashboards/details/rejected/"+init.id[activePoints[0]._index];
                    }
                }
                @endif
            }
        };

        /*
            Вывод основной диаграммы конверсии
        */
        var ctx = document.getElementById('SalonChart').getContext('2d');
        var myBarChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: [],
            datasets: [doneData, allData, rejectedData],
        },
        options: chartOptions
        });


    @elseif($detailsType == "ages")

        let allData = {
            label: 'Возраст',
            data: [],
            borderWidth: 2,
            hoverBorderWidth: 0,
            backgroundColor: 'rgba(17,120,153, .6)'
        };

        let chartOptions = {
            scales: {
                yAxes: [{
                    barPercentage: 0.9,
                    stacked: true
                }],
                xAxes: [{
                    stacked: true
                }],
            },
            elements: {
                rectangle: {
                    borderSkipped: 'left',
                }
            }
        };

        /*
            Вывод диаграммы возрастной структуры
        */
        var ctx = document.getElementById('SalonChart').getContext('2d');
        var myBarChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: [],
            datasets: [allData],
        },
        options: chartOptions
        });

    @endif

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

            $("#leadDateFrom, #sortType, #leadDateTo, #userGroup").change(function () {
                init.ajaxSetPeriod();
            });

        });

    </script>
@endsection
