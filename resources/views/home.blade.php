@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <div class="leed-requests">
        <h1 class="content_header__h1">Заявки с сайта</h1>

        <div class="content_header__dp">
            {{--<div class="date_picker_input">--}}
            {{--{!! Form::label('date', ' ', array('class' => 'control-label')); !!}--}}
            {{--<div>--}}
            {{--{!! Form::text('date_2', '', array('id' => 'date_2', 'disabled')); !!}--}}
            {{--{!! Form::text('to', '', array('id' => 'to', 'hidden')); !!}--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="date_picker_input">--}}
            {{--{!! Form::label('date', ' ', array('class' => 'control-label')); !!}--}}
            {{--<div>--}}
            {{--{!! Form::text('date_1', '', array('id' => 'date_1', 'disabled')); !!}--}}
            {{--{!! Form::text('from', '', array('id' => 'from', 'hidden')); !!}--}}
            {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
@stop

@section('content')

    <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
        <li class="nav-item active">
            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#allLeed" role="tab"
               aria-controls="pills-tabpanel" aria-selected="true">Все лиды</a>
        </li>
        @if(!Auth::user()->manager)
            {{--<li class="nav-item">--}}
                {{--<a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#periodLeed" role="tab"--}}
                   {{--aria-controls="pills-periodLeed" aria-selected="false">Лиды по регионам</a>--}}
            {{--</li>--}}
        @endif
    </ul>



    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane active" id="allLeed" role="tabpanel" aria-labelledby="pills-tabpanel-tab">
            <div class="sort-bar">
                {!! Form::text('range', '', ['id' => 'range', 'hidden']); !!}
                <div class="sort-bar__left">
                    <a href="javascript:void(0);" style="display: inline-block" id="all_request"></a>
                    {{--<a href="javascript:void(0);" id="sort-name">Сортировка по: <select>--}}
                    {{--<option value="Имя">Имени</option>--}}
                    {{--<option value="Имя">Городу</option>--}}
                    {{--</select></a>--}}
                    {{--<a href="javascript:void(0);" id="dealer_steko">Дилер Steko</a>--}}
                </div>
                {{--<div class="sort-bar__right">--}}
                {{--<a href="javascript:void(0);" id='day'>За день</a>--}}
                {{--<a href="javascript:void(0);" id='month'>За месяц</a>--}}
                {{--<a href="javascript:void(0);" id='year'>За год</a>--}}
                {{--<a href="javascript:void(0);" id="random">Произвольный период</a>--}}
                {{--</div>--}}
            </div>

            @if(!Auth::user()->manager)
                <br>
                <div class="row">
                    {!! Form::label('region_id', 'Фильтр по городу' , ['class' => 'col-md-2']); !!}
                    <div class="col-md-2">
                        <div class="input-group">
                            {!! Form::select('region_id', $regions, null,
                             ['id' => 'region_id', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;']) !!}
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <table id="leeds-table" class="display responsive no-wrap leed-requests " cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            {{--<td>ID</td>--}}
                            <td>Время входа</td>
                            <td>Регион</td>
                            <td>Имя</td>
                            <td>Телефон</td>
                            <td>Статус</td>
                            <td>Комментарий</td>
                            <?php
                            if(Auth::user()->manager){
                            ?>
                            <td>Действие</td>
                            <?php
                            }
                            ?>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if(!Auth::user()->manager)

            {{--<div class="tab-pane fade" id="periodLeed" role="tabpanel" aria-labelledby="pills-periodLeed-tab">--}}
                {{--<div class="row">--}}
                    {{--<form action="">--}}
                        {{--<lable>Выберите дату--}}
                            {{--<input type="date" name="">--}}
                        {{--</lable>--}}
                    {{--</form>--}}
                {{--</div>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-md-12">--}}
                        {{--<table class="table table-bordered success" id="leedsRegion">--}}
                            {{--<thead>--}}
                            {{--<tr>--}}
                                {{--<td>Регион</td>--}}
                                {{--<td>Количество заявок</td>--}}
                                {{--<td>Обработвнно заявок</td>--}}
                                {{--<td>Период</td>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
    </div>
    @endif
@stop


@section('tmp_js')
    <script>
        table = null;
        $(document).ready(function () {
            var table = $('#leeds-table').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "<?php echo route('get-leeds') ?>",
                    "method": "POST",
                    "data": function (d) {

                        d._token = $("input[name='_token']").val();
                        d.region_id = $("#region_id").val();
                        d.date_from = $("#from").val();
                        d.date_to = $("#to").val();
                        d.date_range = "day";
                    }
                },
                columns: [
                    // {data: 'id', name: 'id', width: "5%"},
                    {data: 'created_at', name: 'created_at', width: "15%"},
                    {data: 'region', name: 'region', width: "15%"},
                    {data: 'leed_name', name: 'leed_name', width: "1%"},
                    {data: 'leed_phone', name: 'leed_phone', orderable: false, width: "15%"},
                    // {data: 'managers', name: 'managers', orderable: false, searchable: false},
                    {data: 'statuses', name: 'statuses', orderable: false, searchable: false, width: "15%"},
                    {data: 'comment', name: 'comment', orderable: false, width: "15%"},

                        <?php
                        if(Auth::user()->manager){
                        ?>
                    {
                        data: 'btns', name: 'btns', orderable: false, searchable: false, width: "5%"
                    }
                    <?php
                    }
                    ?>
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
            $("#leeds-table_filter").prependTo($(".content_header__dp"));
            $("#leeds-table_length").insertAfter($("#leeds-table"));
            $("#leeds-table_info").prependTo($("#all_request"));
            $("<img id='magnify_icon' src='/img/magnify.png'/>").appendTo($("#leeds-table_filter"));

            //Обновление таблицы datatable
            setInterval(function () {
                table.ajax.reload(null, false);
            }, 60000);

            $('#leeds-table tbody').on('click', 'button', function () {
                var data = table.row($(this).parents('tr')).data();
                var leed_id = data['id'];
                // var manager_id = $('#leed_manager_'+leed_id+' :selected ').val();
                var status_id = $('#leed_status_' + leed_id + ' :selected ').val();
                var comment = $('#leed_comment_' + leed_id).val();
                var token = $("input[name='_token']").val();

                $.ajax({
                    url: "<?php echo route('update-leeds') ?>",
                    method: 'POST',
                    data: {
                        leed_id: leed_id,
                        // manager_id:manager_id,
                        status_id: status_id, comment: comment, _token: token
                    },
                    success: function (data) {
                        table.ajax.reload();
                    }
                });

            });
            $(function () {
                var dateFormat = "mm/dd/yy",
                    from = $("#date_1")
                        .datepicker({
                            // defaultDate: "+1w",
                            changeMonth: true,
                            numberOfMonths: 1
                        })
                        .on("change", function () {
                            to.datepicker("option", "minDate", getDate(this));
                        }),
                    to = $("#date_2").datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        numberOfMonths: 1
                    })
                        .on("change", function () {
                            from.datepicker("option", "maxDate", getDate(this));
                        });

                function getDate(element) {
                    var date;
                    try {
                        date = $.datepicker.parseDate(dateFormat, element.value);
                    } catch (error) {
                        date = null;
                    }

                    return date;
                }
            });
            $("#date_1").change(function () {


                var from_val = $.datepicker.formatDate('yy-mm-dd', new Date($("#date_1").val()));
                var to_val = '';
                if ($("#date_2").val().length > 0) {
                    to_val = $.datepicker.formatDate('yy-mm-dd', new Date($("#date_2").val()));
                }

                $("#from").val(from_val);
                $("#to").val(to_val);


                table.ajax.reload();
            });

            $("#date_2").change(function () {
                var from_val = '';
                if ($("#date_1").val().length > 0) {
                    from_val = $.datepicker.formatDate('yy-mm-dd', new Date($("#date_1").val()));
                }
                var to_val = $.datepicker.formatDate('yy-mm-dd', new Date($("#date_2").val()));

                $("#from").val(from_val);
                $("#to").val(to_val);

                table.ajax.reload();
            });

            function disableDate(bool) {
                $('#date_1, #date_2').prop('disabled', bool);
                $('#date_1, #date_2').css({'backgroundColor': "#ffffff"});
            }

            $(".sort-bar a").click(function () {
                $(".sort-bar a").css('text-decoration', 'none');

                $("#from").val('');
                $("#to").val('');
                $("#date_1").val('');
                $("#date_2").val('');

                if ($(this).attr('id') == 'random') {
                    disableDate(false);
                } else if ($(this).attr('id') == 'year') {
                    disableDate(true);
                    $("#range").val('year');
                    table.ajax.reload();
                } else if ($(this).attr('id') == 'month') {
                    disableDate(true);
                    $("#range").val('month');
                    table.ajax.reload();
                } else if ($(this).attr('id') == 'day') {
                    disableDate(true);
                    $("#range").val('day');
                    table.ajax.reload();
                }
                $(this).css('text-decoration', 'underline');
            });
            $("#leeds-table_filter input").focusin(function () {
                $("#leeds-table_filter").animate({
                    "width": "170px"
                });

            });
            $("#leeds-table_filter input").focusout(function () {
                $("#leeds-table_filter").animate({
                    "width": "40px"
                });
            });
            $("#magnify_icon").click(function () {
                if ($("#leeds-table_filter input").width() == 36) {
                    $("#leeds-table_filter input").focus();
                } else {
                    $("#leeds-table_filter input").val(" ");
                }

            });

            $("#region_id").change(function () {
                table.ajax.reload();
            });

        });
    </script>

@endsection
