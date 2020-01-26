@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    @include('partials.form-status')
    <br>
    <h1>Ежедневный отчет</h1>
@stop

@section('content')
    <br>
    @if(!Auth::user()->analyst)
    <div class="row">
        <div class="col-md-1">
            <a href="/daily-reports/create" style="position: absolute;bottom: 0;" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="Создать план">Создать</a>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <table id="daily-report-table" class="display responsive no-wrap" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>ID</td>
                    <td>Менеджер</td>
                    <td>Дата</td>
                    <td>Количество входящих звонков</td>
                    <td>Количество исходящих звонков</td>
                    <td>Количество клиентов</td>
                    <td>Количество просчетов</td>
                    <td>Количество конструкций в просчете</td>
                    <td>Общая сумма просчетов</td>
                    <td>Количество счетов</td>
                    <td>Количество конструкций в счетах</td>
                    <td>Общая сумма в счетах</td>
                    <td>Количество оплат</td>
                    <td>Количество конструкций в оплатах</td>
                    <td>Общая сумма в оплатах</td>
                    <td>Количество обработанных заявок</td>
                    <td>Направленно на замер</td>
                    @if(!Auth::user()->analyst)
                        <td></td>
                    @endif
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@stop


@section('tmp_js')
    <script>
        table = null;
        $(document).ready(function () {
            var table = $('#daily-report-table').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "<?php echo route('get-daily-reports') ?>",
                    "method": "POST",
                    "data": function (d) {

                        d._token = $("input[name='_token']").val();

                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'date', name: 'date'},
                    {data: 'count_in_calls', name: 'count_in_calls'},
                    {data: 'count_out_calls', name: 'count_out_calls'},
                    {data: 'count_clients', name: 'count_clients'},
                    {data: 'count_culations', name: 'count_culations'},
                    {data: 'count_framework_culations', name: 'count_framework_culations'},
                    {data: 'common_culations', name: 'common_culations'},
                    {data: 'count_bills', name: 'count_bills'},
                    {data: 'count_framework_bills', name: 'count_framework_bills'},
                    {data: 'common_sum_bills', name: 'common_sum_bills'},
                    {data: 'count_payments', name: 'count_payments'},
                    {data: 'count_framework_payments', name: 'count_framework_payments'},
                    {data: 'common_sum_payments', name: 'common_sum_payments'},
                    {data: 'count_done_leeds', name: 'count_done_leeds'},
                    {data: 'direct_sample', name: 'direct_sample'},
                    @if(!Auth::user()->analyst)
                        {data: 'btn', name: 'btn', width: "5%", orderable: false, searchable: false}
                    @endif
                ],
                language: {
                    "processing": "Подождите...",
                    "search": "Поиск:",
                    "lengthMenu": "Показать _MENU_ записей",
                    "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                    "infoEmpty": "Записи с 0 до 0 из 0 записей",
                    "infoFiltered": "(отфильтровано из _MAX_ записей)",
                    "infoPostFix": "",
                    "loadingRecords": "Загрузка записей...",
                    "zeroRecords": "Записи отсутствуют.",
                    "emptyTable": "В таблице отсутствуют данные",
                    "paginate": {
                        "first": "Первая",
                        "previous": "Предыдущая",
                        "next": "Следующая",
                        "last": "Последняя"
                    },
                    "aria": {
                        "sortAscending": ": активировать для сортировки столбца по возрастанию",
                        "sortDescending": ": активировать для сортировки столбца по убыванию"
                    }
                }
            });


        });
    </script>
@endsection