@extends('adminlte::page')

@section('title', 'Steko')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.0/css/responsive.dataTables.min.css">

@section('content_header')
    <h1>История</h1>
@stop

@section('content')
    <div class="col s12 center">
        <table id="cars-table" class="display responsive no-wrap" cellspacing="0" width="100%">
            <thead>
            <tr>
                <td data-orderable="false">ID</td>
                <td data-orderable="false">Номер машины</td>
                <td data-orderable="false">Группа</td>
                <td data-orderable="false">Поставщик</td>
                <td data-orderable="false">Выехал</td>
                <td data-orderable="false">Бухгалтер</td>
                <td data-orderable="false">Снабжение</td>
                <td data-orderable="false">Кладовщик</td>
                <td data-orderable="false">КРУ</td>
                <td data-orderable="false">Технолог</td>
                {{--<td data-orderable="false">ОТК</td>--}}
                <td data-orderable="false">Действие</td>
            </tr>
            </thead>
        </table>
    </div>

@section('users_js')
    <script type="text/javascript">
        table = null;
        $(document).ready(function () {
            table = $('#cars-table').DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "order": [[ 0, "desc" ]],
                "ajax": {
                    "url": "<?php echo route('cars-history') ?>",
                    "method": "POST",
                    "data": function (d) {

                        d._token = $("input[name='_token']").val();
                    }
                },
                "columns": [
                    {"data": "id"},
                    {"data": "car_number"},
                    {"data": "groupe"},
                    {"data": "provider"},
                    {"data": "updated_at"},
                    {"data": "booker"},
                    {"data": "supply"},
                    {"data": "stockman"},
                    {"data": "kry"},
                    {"data": "technologist"},
                    // {"data": "otk"},
                    {"data": "actions"}
                ],
                "language": {
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
                },
            });
            $('#cars-table_filter').hide();
            $('#cars-table_length').hide();
        });
    </script>

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.0/js/dataTables.responsive.min.js"></script>

@endsection
@stop