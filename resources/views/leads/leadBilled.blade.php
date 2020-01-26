@extends('adminlte::page')

@section('title', 'Steko')
@section('content_header')

    <div class="leed-requests">
        <div class="row">
            <div class="col-md-4">
                <h1 class="content_header__h1">Выставлен счет по лиду</h1>
                @if(\Illuminate\Support\Facades\Auth::user()->role_id == 3 ||
                \Illuminate\Support\Facades\Auth::user()->role_id == 5)
                    <a href="{{route('storeLead')}}" class="btn btn-success">Создать</a>
                @endif
            </div>
            @if(\Illuminate\Support\Facades\Auth::user()->role_id == 3 ||
               \Illuminate\Support\Facades\Auth::user()->role_id == 5)
                <div class="col-md-8">
                    <button type="button" class="btn btn-default"
                            data-container="body" data-toggle="popover"
                            data-placement="left"
                            data-content="Ведется работа по выяснению контактных данных и потребностей клиента">
                        В обработке
                    </button>
                    <button type="button" class="btn btn-default"
                            data-container="body" data-toggle="popover"
                            data-placement="top" data-content="Клиент записан на замер">
                        Замер
                    </button>
                    <button type="button" class="btn btn-default"
                            data-container="body" data-toggle="popover"
                            data-placement="bottom"
                            data-content="Ведется работа по подготовке и выставлению предложения для клиента (после замера или заказ по своим размерам)">
                        Предложение
                    </button>
                    <button type="button" class="btn btn-default"
                            data-container="body" data-toggle="popover"
                            data-placement="top" data-content="Сформирован и выставлен счет на оплату">
                        Выставлен счет
                    </button>
                    <button type="button" class="btn btn-default"
                            data-container="body" data-toggle="popover"
                            data-placement="right" data-content="Произведена оплата по выставленном счету">
                        Оплачен
                    </button>
                </div>
            @endif
        </div>
    </div>
@stop

@section('content')
    <div class="col-md-12">
        <form id="leadDatePicker">
            <div class="form-group form-group-sm">
                <label>Дата с
                    <input type="date" name="leadDateFrom" id="leadDateFrom" class="form-control input-sm" value="{{\Carbon\Carbon::make(\App\Leed::dateFromLead()[0])->format('Y-m-d')}}">
                </label>
                <label>Дата по
                    <input type="date" name="leadDateTo" id="leadDateTo" class="form-control input-sm" value="{{\Carbon\Carbon::make(\App\Leed::dateFromLead()[1])->format('Y-m-d')}}">
                </label>
                <label>Регион
                    <select name="leed_region_id[]" id="leadRegionId" class="multiselect-ui form-control form-control-sm" multiple="multiple">
                        @foreach(\App\Regions::getUserRegions() as $region)
                            <option value="{{$region['id']}}">{{$region['name']}}</option>
                        @endforeach
                    </select>
                </label>
                @if(\Illuminate\Support\Facades\Auth::user()->role_id != 3)
                    <label>Менеджер
                        <select name="user_id[]" id="leadsUserId"  class="multiselect-ui form-control form-control-sm" multiple="multiple">
                            @foreach(\App\User::userManager() as $manager)
                                <option value="{{$manager['id']}}">{{$manager['name']}}</option>
                            @endforeach
                            <option value="0">Не распределен</option>
                        </select>
                    </label>
                @endif
                {{--<input type="submit" class="btn btn-success btn-sm" value="ok">--}}
            </div>
        </form>
    </div>
    <table class="table table-bordered" id="leads">
        <thead>
        <tr>
            <th>Дата</th>
            <th>Лид</th>
            <th>Регион</th>
            <th>Имя</th>
            <th>Телефон</th>
            <th>Статус</th>
            @if(\Illuminate\Support\Facades\Auth::user()->manager || \Illuminate\Support\Facades\Auth::user()->role_id <= 2)
                <th>Отказ</th>
            @endif
            <th>Мененджер</th>
            <th>Комментарий</th>
            <th>Статус исполнения</th>
            @if(\Illuminate\Support\Facades\Auth::user()->role_id == 5)
                <th>Мененджер Call-Centre</th>
            @endif
            @if(\Illuminate\Support\Facades\Auth::user()->manager)
                <th>Действие</th>
            @endif
        </tr>
        </thead>
    </table>
@stop

@section('tmp_js')
    <script>
        $(document).ready(function () {
            $('.multiselect-ui').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                defaultChecked:true,
                nonSelectedText: 'Фильтр',
                selectAllText: 'Все',
                allSelectedText: 'Все'

            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });

            let leadTable = $('#leads').DataTable({
                order: [['0', "desc"]],
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '{!! route('leadBilled')!!}',
                    "method": "POST",
                    'data': function (d) {
                        d.leadDateFrom = $('#leadDateFrom').val();
                        d.leadDateTo = $('#leadDateTo').val();
                        d.leadRegionId = $('#leadRegionId').val();
                        d.leadsUserId = $('#leadsUserId').val();
                    }
                },
                "createdRow": function (row, data, dataIndex) {
                    if (data.rejected_lead == 1) {
                        $(row).css('background-color', '#ff7878');
                    }
                },
                columns: [
                    {data: 'created_at', name: 'created_at', width: '15%'},
                    {data: 'leed_receive_id', render: function (data) {
                            if (data == 1) {
                                return `<img src="https://img.icons8.com/color/48/000000/europe.png">`;
                            } else if (data == 2) {
                                return `<img src="https://img.icons8.com/color/48/000000/phone-office.png">`;
                            } else if (data == 3) {
                                return `<img src="https://img.icons8.com/color/48/000000/friends.png">`;
                            }
                        }},
                    {data: 'region', name: 'region'},
                    {data: 'leed_name', name: 'leed_name'},
                    {data: 'leed_phone', name: 'leed_phone'},
                    {data: 'status', name: 'status', width: '20%'},
                        @if(\Illuminate\Support\Facades\Auth::user()->manager || \Illuminate\Support\Facades\Auth::user()->role_id <= 2)
                    {
                        data: 'reject', name: 'reject'
                    },
                        @endif
                    {
                        data: 'manager', name: 'manager', orderable: false, searchable: false, width: '15%'
                    },
                    {data: 'comment', name: 'comment', orderable: false, searchable: false,},
                    {data: 'status_id', name: 'status_id'},
                        @if(\Illuminate\Support\Facades\Auth::user()->role_id == 5)
                    {
                        data: 'managerCall', name: 'managerCall', orderable: false, searchable: false, width: '15%'
                    },
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->manager)
                    {
                        data: 'btns', name: 'btns', orderable: false, searchable: false,
                    },
                    @endif
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

            $(function () {
                $('[data-toggle="popover"]').popover()
            });
            $("#leadDateFrom").change(function () {
                leadTable.ajax.reload();
            });
            $("#leadDateTo").change(function () {
                leadTable.ajax.reload();
            });
            $("#leadRegionId").change(function () {
                leadTable.ajax.reload();
            });
            $("#leadsUserId").change(function () {
                leadTable.ajax.reload();
            });

        });
    </script>
@endsection

