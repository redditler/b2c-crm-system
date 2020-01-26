@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    @include('partials.form-status')
    <div class="title-page">
        <h1 class="title-page__name">План на месяц</h1>
    </div>
    <div style="display: inline-block; float: right" id="header_monthly_plan"></div>
@stop

@section('content')

    <br>
    <div class="row">
        <div class="col-md-1">
            <a href="/monthly-plan/create" style="position: absolute;bottom: 0;" class="btn btn-success btn-xs typical-coral-btn"
               data-toggle="tooltip" data-placement="bottom" title="Создать план">Создать</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table id="monthly-plan-table" class="display responsive no-wrap table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <!-- <td>ID</td> -->
                    <td>Год</td>
                    <td>Месяц</td>
                    <td>Филиал</td>
                    <td>Конструкций</td>
                    <td>Сумма</td>
                    @if(Auth::user()->role_id <= 2)
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
                    // {data: 'id', name: 'id'},
                    {data: 'year', name: 'year'},
                    {data: 'month', name: 'month', searchable: false},
                    {data: 'branch_id', name: 'branch_id'},
                    {data: 'frameworks', name: 'frameworks'},
                    {data: 'sum', name: 'sum'},
                        @if(Auth::user()->role_id <= 2)
                    {
                        data: 'active', name: 'active'
                    },
                    {data: 'btn', name: 'btn', width: "5%", orderable: false, searchable: false}
                    @endif

                ],
                fixedHeader: {
                    header: true
                },
                language: {
                    "processing": "Подождите...",
                    "search": " ",
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
            $("#monthly-plan-table_filter").appendTo($("#header_monthly_plan"));
            $("<img id='magnify_icon' src='/img/magnify.png'/>").appendTo($("#monthly-plan-table_filter"));
            $("#monthly-plan-table_filter input").focusin(function () {
                $("#monthly-plan-table_filter").animate({
                    "width": "170px"
                });

            });
            $("#monthly-plan-table_filter input").focusout(function () {
                $("#monthly-plan-table_filter").animate({
                    "width": "40px"
                });
            });
            $("#magnify_icon").click(function () {
                if ($("#monthly-plan-table_filter input").width() == 36) {
                    $("#monthly-plan-table_filter input").focus();
                } else {
                    $("#monthly-plan-table_filter input").val(" ");
                }

            });

        });
    </script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">
@endsection