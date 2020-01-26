@extends('adminlte::page')

@section('content_header')
    <div class="headerNamePage">
        <h2 style="color:white">Dashboards</h2>
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
                               value="{{!empty(request()->session()->get('period')) ? request()->session()->get('period')['leadDateTo'] : \Carbon\Carbon::make(\App\Leed::dateFromLead()[0])->format('Y-m-d')}}">
                    </div>
                </div>
            </form>
        </div>
        <div class="dashboards-container">
            <div class="dashboards-items">
                <div class="branch_dashboard dashboard-item">
                    <div class="statistics-view">
                        <canvas id="SalonChart" height="110" style="background-color:#fff;" data-branch="{{ $branch_id }}"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('tmp_js')
    <script>
        let init = {
            all: [],
            done: [],
            names: [],
            conversions: [],
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
                        leadDateFrom: $('#leadDateFrom').val(),
                        leadDateTo: $('#leadDateTo').val(),
                        branchId: $('#SalonChart').data('branch')
                    },
                    success: function(data) {

                        init.id = [];
                        init.conversions = [];
                        init.names = [];
                        init.done = [];
                        init.all = [];
                        init.rejected = [];

                        data.forEach(function(item) {
                            init.conversions.push(item.conversion);
                            init.names.push(item.name);
                            init.all.push(item.all);
                            init.done.push(item.done);
                            init.rejected.push(item.rejected);
                            $('.headerNamePage h2').text(item.branch_name);
                        });

                        allData.data = init.all;
                        doneData.data = init.done;
    @if($detailsType == "rejected")
                        rejectedData.data = init.rejected;
    @endif
                        myBarChart.data.labels = init.names;
                        myBarChart.update();
                    }
                });
            }
        };
    @if($detailsType == "rejected")
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
            backgroundColor: 'rgba(153,20,26,0.6)'
        };
    @else
        let doneData = {
            label: 'Оплачено',
            data: [],
            borderWidth: 2,
            hoverBorderWidth: 0,
            backgroundColor: 'rgba(13,153,89,0.6)'
        };
        let allData = {
            label: 'Всего создано',
            data: [],
            borderWidth: 2,
            hoverBorderWidth: 0,
            backgroundColor: 'rgba(17,120,153, .6)'
        };
    @endif
        let chartOptions = {
            scales: {
                yAxes: [{
                    barPercentage: 0.9,
                    stacked: false
                }],
                xAxes: [{
                    stacked: false
                }]
            },
            animation: {
                duration: 0,
                onComplete: function () {
                    var flag = 0;
                    var ctx = this.chart.ctx;
                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'normal', Chart.defaults.global.defaultFontFamily);
                    ctx.fillStyle = 'rgba(225, 225, 225, 1)';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    this.data.datasets.forEach(function (dataset) {
                        for (var i = 0; i < dataset.data.length; i++) {
                            var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model;
                            if(flag > 0) {
                                ctx.fillText(init.conversions[i] + ' %', model.x - 30, model.y + 7);
                            }
                        }
                        flag++;
                    });
                }},
            elements: {
                rectangle: {
                    borderSkipped: 'left',
                }
            }
        };
        var ctx = document.getElementById('SalonChart').getContext('2d');
        var myBarChart = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: [],
    @if($detailsType == "rejected")
                datasets: [rejectedData, doneData, allData],
    @else
                datasets: [doneData, allData],
    @endif
            },
            options: chartOptions
        });

        $(document).ready(function(){
            var start = false;
            function startScript() {
                if(!start)
                    init.ajaxSetPeriod();
                start = true;
            }
            startScript();

            $('#leadDateFrom, #leadDateTo').change(function () {
                init.ajaxSetPeriod();
            });
        });

    </script>
@endsection