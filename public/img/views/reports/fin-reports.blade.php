@extends('adminlte::page')
@section('title', 'Steko')
@section('content_header')
    @include('partials.form-status')
    <div class="leed-requests">
        <h1 class="content_header__h1" style="margin-top: 5px">Финансовый отчет</h1>
        {{--<div class="form-group date_picker_input fin-rep_header_date">--}}
            {{--{!! Form::text('', old('date'), array('id' => 'datepicker', 'name'=>'date', 'class' => 'form-control')) !!}--}}
        {{--</div>--}}
    </div>
    <h1></h1>
@stop
@section('content')
    <br>
    <div class="row">
        <div class="col-md-12">
            <table id="fin-plan-table" class="display responsive no-wrap" cellspacing="0" width="100%">
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
                    @if(Auth::user()->manager)
                        <td id="button_edit"></td>
                    @endif
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @if(Auth::user()->manager)
    <br>
    <div class="row">
        <div class="col-md-12">
            <a href="/fin-reports/create" class="btn btn-block fin-rep-btn-confirm" title="Добавить еще"><img style='margin-right: 10px' src="/img/plus.png" alt="">Добавить еще</a>
        </div>
    </div>
    @endif
@stop
@section('tmp_js')
    <script>
        $(function () {
            $("#datepicker").datepicker({
                // showOn: "button",
                // buttonImage: "/img/calendar.gif",
                // buttonImageOnly: true,
                // buttonText: "Select date",
                dateFormat: "dd.mm.yy"
            });
        });
        table = null;
        $(document).ready(function () {
            var table = $('#fin-plan-table').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "<?php echo route('get-fin-plans') ?>",
                    "method": "POST",
                    "data": function (d) {
                        d._token = $("input[name='_token']").val();
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
                    @if(Auth::user()->manager)
                        {data: 'btn', name: 'btn', width: "5%", orderable: false, searchable: false, id: "asd"}
                    @endif

                ],
                language: {
                    "processing": "Подождите...",
                    "search": "Поиск:",
                    "lengthMenu": "Показать _MENU_ записей",
                    "info": "",
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
            $(".dataTables_filter").hide();
            $(".dataTables_length").hide();
            $(".dataTables_info").hide();

        });
    </script>
@endsection