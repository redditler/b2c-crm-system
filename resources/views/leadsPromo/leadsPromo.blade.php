@extends('adminlte::page')

@section('content')
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    
    @php
        $manager = \App\Support\Support::contenHeaderManager();
    @endphp

    <section class="content__wrapper title-style" data-id="promo-lead">
        <div class="container">
            <div class="container__title">
                <h1 class="title">Акции</h1>
            </div>
            <div class="container table">
                <div class="container__filters">
                        @include('leads.filter.leadFilter')
                        @include('leads.leadModalOneClient')
                </div>
                <div class="container__table">
                    <table class="table dataTable promo-lead" id="leads">
                        <thead class="table__head">
                            <tr class="head__row">
                                <th class="row__title">Дата</th>
                                <th class="row__title">Регион</th>
                                <th class="row__title">Имя</th>
                                <th class="row__title">Телефон</th>
                                <th class="row__title">Промокод</th>
                                <th class="row__title">Мененджер</th>
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
    <script src="{{asset('js/users/userIndex.js')}}"></script>
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('js/lead/leadFilter.js')}}"></script>
    <script>
            $(document).ready(function () {
                $('#sectorGroupFilter').delegate('select', 'change', function () {
                    leadTable.ajax.reload();
                    //filerLead();
                });
                $('#sectorRegionManagerFilter ').delegate('select', 'change', function () {
                    leadTable.ajax.reload();
                    //filerLead();
                });
                $('#sectorSalonFilter ').delegate('select', 'change', function () {
                    leadTable.ajax.reload();
                    //filerLead();
                });
                $('#sectorManagerFilter ').delegate('select', 'change', function () {
                    leadTable.ajax.reload();
                    //filerLead();
                });

            let leadTable = $('#leads').DataTable({
                order: [['0', "desc"]],
                processing: true,
                serverSide: true,
                ajax: {
                    "url": '/indexLeadsPromo',
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
                "createdRow": function( row, data, dataIndex){
                    if( data.rejected_lead ==  1){
                        $(row).css('background-color','#ff7878');
                    }
                },
                fixedHeader: {
                    header: true
                },
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'region', name: 'region', orderable: false},
                    {data: 'leed_name', name: 'leed_name', orderable: false},
                    {data: 'leed_phone', name: 'leed_phone', orderable: false},
                    {data: 'promo_code', name: 'promo_code', orderable: false},
                    {data: 'manager', name: 'manager', orderable: false, searchable: false, width: '15%'},
                ],
                "drawCallback": function (settings) {

                    $('.numberOfLeadsPerClient').click(function () {
                        $('#leadOneClientModal').modal('toggle');

                        $.ajax({
                            method: 'post',
                            url: '/oneClientLead',
                            data: {id: $(this).val()},
                            success: function (result) {
                                $(`#leadOneClientModalContentTBody`).html('');
                                for (let index in result.reverse()) {
                                    $(`#leadOneClientModalContentTBody`).append(
                                        `<tr>
                                            <td>${result[index].created_at}</td>
                                            <td>${result[index].region.name}</td>
                                            <td>${result[index].leed_name}</td>
                                            <td>${result[index].leed_phone}</td>
                                            <td> <div class="progress-bar">
                                                    <div class="name-bar"><span></span></div>
                                                    <div class="progress-bar-block">
                                                        <div class="progres-1"></div>
                                                        <div class="progres-2"></div>
                                                        <div class="progres-3"></div>
                                                        <div class="progres-4"></div>
                                                        <div class="progres-5"></div>
                                                        <div class="progres-6"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>`
                                    );
                                    let colorList = ['#01d2f0', '#00afc8', '#39485f', '#1f2c4e', '#fd6f75', '#ff9099'];
                                    let statusList = ['Новый', 'Обработка', 'Замер', 'Предложение', 'Выставлен счёт', 'Оплачен'];
                                    let statusId = [5, 11, 12, 13, 14, 15];

                                    for (let i = 0; i < statusId.length; i++) {
                                        if (result[index].status.id == statusId[i]) {
                                            $('.progress-bar-block').addClass(`progress-${i}`);
                                            $('.name-bar').addClass(`progress-name-${i}`);
                                        }
                                    }

                                    for (let i = 0; i < 6; i++) {
                                        for (let p = 0; p < i + 1; p++) {
                                            $(`.progress-bar-block.progress-${i} .progres-${p + 1}`).css('background-color', colorList[p]);
                                        }
                                        $(`.progress-name-${i}`).html(`<span>${statusList[i]}</span>`);
                                    }
                                }
                            }
                        })
                    });
                },


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
                $("#leadStatusId").change(function () {
                    leadTable.ajax.reload();
                });
                $("#leadsUserId").change(function () {
                    leadTable.ajax.reload();
                });
        });
    </script>
@endsection

