@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    @include('partials.form-status')
    <br>
    <h1>План на месяц</h1>
@stop

@section('content')
    <br>
    <div class="row">
        <div class="col-md-1">
            <a href="/monthly-plan/create" style="position: absolute;bottom: 0;" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="Создать план">Создать</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table id="monthly-plan-table" class="display responsive no-wrap" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>ID</td>
                    <td>Год</td>
                    <td>Месяц</td>
                    <td>Филиал</td>
                    <td>Конструкций</td>
                    <td>Сумма</td>
                    @if(Auth::user()->analyst)
                        <td>Активно</td>
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
            var table = $('#monthly-plan-table').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "<?php echo route('get-monthly-plans') ?>",
                    "method": "POST",
                    "data": function (d) {

                        d._token = $("input[name='_token']").val();

                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'year', name: 'year'},
                    {data: 'month', name: 'month', searchable: false},
                    {data: 'branch_id', name: 'branch_id'},
                    {data: 'frameworks', name: 'frameworks'},
                    {data: 'sum', name: 'sum'},
                    @if(Auth::user()->analyst)
                        {data: 'active', name: 'active'},
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