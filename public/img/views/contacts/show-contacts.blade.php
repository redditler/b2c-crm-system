@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')

    <h1 class="content_header__h1">Контакты</h1>
    <div class="leed-right-menu">
        <a href="/contacts/create" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="Создать контакт"><img src="/img/add_contact.png"/>Добавить контакт</a>
    </div>
@stop

@section('content')
    @include('partials.form-status')

    <div class="sort-bar">
        <div class="sort-bar__left">
            <a href="javascript:void(0);" style="display: inline-block" id="all_request"></a>
            {{--<a href="javascript:void(0);" id="sort-name">Сортировка по: <select>--}}
                    {{--<option value="Имя">Имени</option>--}}
                    {{--<option value="Имя">Городу</option>--}}
                {{--</select></a>--}}
            {{--<a href="javascript:void(0);" id="dealer_steko">Дилер Steko</a>--}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table id="contacts-table" class="display responsive no-wrap" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>ФИО</td>
                    <td>Город</td>
                    <td>Менеджер</td>
                    <td>Телефон</td>
                    <td>Последние обращение</td>
                    <td></td>
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
            var table = $('#contacts-table').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "<?php echo route('get-contacts') ?>",
                    "method": "POST",
                    "data": function (d) {
                        d._token = $("input[name='_token']").val();
                    }
                },
                columns: [
                    {data: 'fio', name: 'fio', width: "20%"},
                    {data: 'city', name: 'city', width: "20%"},
                    {data: 'manager', name: 'manager', width: "15%"},
                    {data: 'phone', name: 'phone',orderable: false, width: "10%"},
                    {data: 'last_call', name: 'last_call', width: "15%", orderable: false,  searchable: false},
                    {data: 'btn', name: 'btn', width: "5%", orderable: false, searchable: false}
                ],
                language: {
                    "processing": "Подождите...",
                    "search": " ",
                    "lengthMenu": "Показать _MENU_ записей",
                    "info": "_TOTAL_ записей",
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
            $(".dataTables_filter").prependTo($(".leed-right-menu"));
            $(".dataTables_length").insertAfter($(".dataTable"));
            $(".dataTables_info").prependTo($("#all_request"));
            $("<img id='magnify_icon' src='/img/magnify.png'/>").appendTo($(".dataTables_filter"));
            $(".dataTables_filter input").focusin(function () {
                $(".dataTables_filter").animate({
                    "width":"170px"
                });

            });
            $(".dataTables_filter input").focusout(function(){
                $(".dataTables_filter").animate({
                    "width":"40px"
                });
            });
            $("#magnify_icon").click(function(){
                if ($(".dataTables_filter input").width() == 36) {
                    $(".dataTables_filter input").focus();
                } else {
                    $(".dataTables_filter input").val(" ");
                }

            });

        });
    </script>
@endsection