@extends('adminlte::page')

@section('content')
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">

    <section class="content__wrapper title-style" data-id="canceled-lead">
        <div class="container">
            <div class="container__title">
                <h1 class="title">Забракованные лиды</h1>
            </div>
            <div class="container table">
                <div class="container__filters">
                        @include('leads.filter.leadFilter')
                </div>
                <div class="container__table">
                    <table class="table dataTable {{(!(\Illuminate\Support\Facades\Auth::user()->role_id == 3 || \Illuminate\Support\Facades\Auth::user()->role_id == 5)) ? 'table-manager-canceled': ''}}" id="leads">
                        <thead class="table__head">
                            <tr class="head__row">
                                <th class="row__title">Лид</th>
                                <th class="row__title">Дата</th>
                                <th class="row__title">Регион</th>
                                <th class="row__title">Имя</th>
                                <th class="row__title">Телефон</th>
                                <th class="row__title">Статус</th>
                                <th class="row__title">Мененджер</th>
                                <th class="row__title">Комментарий</th>
                                @if(\Illuminate\Support\Facades\Auth::user()->role_id == 5)
                                    <th class="row__title">Мененджер Call-Centre</th>
                                @endif
                                @if(!(\Illuminate\Support\Facades\Auth::user()->role_id == 3 || \Illuminate\Support\Facades\Auth::user()->role_id == 5))
                                    <th class="row__title">Отказ</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="table__body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@stop

@section('tmp_js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="{{asset('js/users/userIndex.js')}}"></script>
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('js/lead/leadFilter.js')}}"></script>
    <script src="{{asset('js/components/progressBar.js')}}"></script>
    
    <script>
        $(document).ready(function () {

             // Применение фильтров Даты, Группы, Региона, Менеджера, Салона
             $('body').on('change', '#leadDateFrom, #leadDateTo, #sectorGroupFilter, #sectorRegionManagerFilter, #sectorSalonFilter, #sectorManagerFilter', function(){
                leadTable.ajax.reload();
            });
            
            let leadTable = $('#leads').DataTable({
                order: [['0', "desc"]],
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '{!! route('leadCanceled')!!}',
                    "method": "POST",
                    'data': function (d) {
                        d.leadDateFrom = $('#leadDateFrom').val();
                        d.leadDateTo = $('#leadDateTo').val();

                        d.group_id = $('#leadGroupSelector').val();
                        d.regionManager_id = $('#leadRegionManagerSelector').val();
                        d.salon_id = $('#leadSalon').val();
                        d.user_id = $('#leadManagerSelector').val();
                    }
                },
                fixedHeader: {
                    header: false
                },
                columns: [
                    { data: 'leed_receive_id', render: function (data) {
                            return `<img src="img/icons/lead-icon-${data}.svg">`;
                        }
                    },
                    { data: 'created_at',   className: 'row--date', name: 'created_at',  orderable: false, },
                    { data: 'region',  className: 'row--region', name: 'region', orderable: false,  },
                    { data: 'leed_name',  className: 'row--name',  name: 'leed_name', orderable: false, },
                    { data: 'leed_phone',  className: 'row--phone',  name: 'leed_phone', orderable: false, },
                    { data: 'status',  name: 'status',  className: 'row--status', orderable: false, },
                    { data: 'manager',   name: 'manager',  className: 'row--manager', orderable: false,   searchable: false, },
                    { data: 'comment',   name: 'comment',  className: 'row--comment',  orderable: false,  earchable: false, },

                    @if(\Illuminate\Support\Facades\Auth::user()->role_id == 5)
                    {  data: 'managerCall',   name: 'managerCall',  className: 'row--call', orderable: false,   searchable: false,  },
                    @endif

                    @if(!(\Illuminate\Support\Facades\Auth::user()->role_id == 3 || \Illuminate\Support\Facades\Auth::user()->role_id == 5))
                    { data: 'reject',  name: 'reject',  className: 'row--btn-set', orderable: false, },
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

            $('.phone_search').on('keyup', function () {
                leadTable.columns(4).search(this.value).draw();
            });

            leadTable.on('draw', function () {
                progressBar();
            });
        });
    </script>
@endsection

