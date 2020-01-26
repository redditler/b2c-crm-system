@extends('adminlte::page')

@section('content_header')
    <div class="headerNamePage">
        <h2 style="color:white">Dashboards</h2>
    </div>
@stop

@section('content')
    <div>
        <div class="filter_menu">
            <form id="leadDatePicker" method="GET">
                <div class="filter_box">
                    <div class="form-group form-group-sm filter_leads_group flex block_filter">
                        <span>Дата</span>
                        <input type="date" name="leadDateFrom" id="leadDateFrom"
                               class="form-control input-sm"
                               value="{{ isset($telFilters['since']) ? $telFilters['since'] : \Carbon\Carbon::make(\App\Leed::dateFromLead()[0])->format('Y-m-d')}}">
                        <input type="date" name="leadDateTo" id="leadDateTo"
                               class="form-control input-sm"
                               value="{{ isset($telFilters['till']) ? $telFilters['till'] : \Carbon\Carbon::make(\App\Leed::dateFromLead()[0])->format('Y-m-d')}}">
                    </div>
                    <div class="group_filter block_filter flex">
    @if(\Illuminate\Support\Facades\Auth::user()->role_id == 1)
                        <span>Группа</span>
                        <label>
                            <select name="group_id" id="userGroup"
                                    class="multiselect-ui form-control form-control-sm">
                                    <option value="2"{{ isset($telFilters['group']) ? ($telFilters['group'] == 2 ? ' selected' : '') : '' }}>Розница</option>
                                    <option value="1"{{ isset($telFilters['group']) ? ($telFilters['group'] == 1 ? ' selected' : '') : '' }}>Онлайн</option>
                            </select>
                        </label>
                        <span>Сортировка</span>
                        <label>
                            <select name="report_sort" id="sortType" class="multiselect-ui form-control form-control-sm">
                                <option value="incoming"{{ isset($telFilters['sort']) ? ($telFilters['sort'] == "incoming" ? ' selected' : '') : '' }}>По входящим</option>
                                <option value="outgoing"{{ isset($telFilters['sort']) ? ($telFilters['sort'] == "outgoing" ? ' selected' : '') : '' }}>По исходящим</option>
                                <option value="summary"{{ isset($telFilters['sort']) ? ($telFilters['sort'] == "summary" ? ' selected' : '') : '' }}>По общему количеству</option>
                            </select>
                        </label>
    @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="dashboards-container">
            <canvas id="SalonChart" height="200" style="background-color:#fff;"></canvas>
        </div>

    </div>

    <div id="pleaseWait" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="width:auto;">
            <div class="modal-content">
                <div class="modal-body" style="padding:0;">
                    <img src="{{ asset('img/preloader.gif') }}" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style type="text/css">
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

        let doneData = {
            label: 'Входящие',
            data: [
    @if(isset($usageData))
        @foreach($usageData as $thisInnerStat)
            @foreach($thisInnerStat as $thisDay=>$thisValues)
                @if($thisDay == "summary")
                    {{ $thisValues->incoming }},
                    @break
                @endif
            @endforeach
        @endforeach
    @endif
            ],
            borderWidth: 2,
            hoverBorderWidth: 0,
            backgroundColor: 'rgba(13,153,89,0.6)'
        };
        let allData = {
            label: 'Исходящие',
            data: [
    @if(isset($usageData))
        @foreach($usageData as $thisInnerStat)
            @foreach($thisInnerStat as $thisDay=>$thisValues)
                @if($thisDay == "summary")
                    {{ $thisValues->outgoing }},
                    @break
                @endif
            @endforeach
        @endforeach
    @endif
            ],
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
            elements: {
                rectangle: {
                    borderSkipped: 'left',
                }
            }
        };

        /*
            Вывод основной диаграммы конверсии
        */
        var ctx = document.getElementById('SalonChart').getContext('2d');
        var myBarChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: [
    @if(isset($usageData))
        @foreach($usageData as $thisSalon=>$thisInnerStat)
                    '{{ isset($branchesResolver[$thisSalon]) ? $branchesResolver[$thisSalon] : $thisSalon }}',
        @endforeach
    @endif
            ],
    @if(@$telFilters['sort'] == "outgoing")
            datasets: [allData, doneData],
    @else
            datasets: [doneData, allData],
    @endif
        },
        options: chartOptions
        });

        $('#leadDateFrom, #leadDateTo, #userGroup, #sortType').change(function () {
            $('#pleaseWait').modal('show');
            $('form#leadDatePicker').submit();
        });

    </script>
@endsection