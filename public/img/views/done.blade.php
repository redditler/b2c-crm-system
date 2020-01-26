@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <div class="leed-requests">
        <h1 class="content_header__h1">Отработанные</h1>
        <div class="content_header__dp">
            <div class="date_picker_input">
                {!! Form::label('date', ' ', array('class' => 'control-label')); !!}
                <div>
                    {!! Form::text('date_2', '', array('id' => 'date_2', 'disabled')); !!}
                    {!! Form::text('to', ' ', array('id' => 'to', 'hidden')); !!}
                </div>
            </div>
            <div class="date_picker_input">
                {!! Form::label('date', ' ', array('class' => 'control-label')); !!}
                <div>
                    {!! Form::text('date_1', ' ', array('id' => 'date_1', 'disabled')); !!}
                    {!! Form::text('from', '', array('id' => 'from', 'hidden')); !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="sort-bar" id="sort-bar">
        {!! Form::text('range', '', array('id' => 'range', 'hidden')); !!}
        <div class="sort-bar__left">

        </div>
        <div class="sort-bar__right">
            <a href="javascript:void(0);" id='day'>За день</a>
            <a href="javascript:void(0);" id='month'>За месяц</a>
            <a href="javascript:void(0);" id='year'>За год</a>
            <a href="javascript:void(0);" id="random">Произвольный период</a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <table id="done-leeds-table" class="display responsive no-wrap" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>ID</td>
                    <td>Время входа</td>
                    <td>Имя</td>
                    <td>Телефон</td>
                    <td>Регион</td>
                    <td>Менеджер</td>
                    <td>Статус</td>
                    <td>Комментарий</td>
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
            var table = $('#done-leeds-table').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "<?php echo route('get-done-leeds') ?>",
                    "method": "POST",
                    "data": function (d) {

                        d._token = $("input[name='_token']").val();
                        d.date_from = $("#from").val();
                        d.date_to = $("#to").val();
                        d.date_range = $("#range").val();
                    }
                },
                columns: [
                    {data: 'id', name: 'id', width: "5%"},
                    {data: 'created_at', name: 'created_at', width: "15%"},
                    {data: 'leed_name', name: 'leed_name', width: "15%"},
                    {data: 'leed_phone', name: 'leed_phone', orderable: false, width: "10%"},
                    {data: 'region', name: 'region', width: "10%"},
                    {data: 'managers', name: 'managers', width: "10%"},
                    {data: 'statuses', name: 'statuses', searchable: false, width: "15%"},
                    {data: 'comment', name: 'comment', orderable: false, width: "20%"}
                ],
                language: {
                    "processing": "Подождите...",
                    "search": " ",
                    "lengthMenu": "Показать _MENU_ записей",
                    "info": "_TOTAL_ записей",
                    "infoEmpty": "0 записей",
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

            $( function() {
                var dateFormat = "mm/dd/yy",
                    from = $( "#date_1" )
                        .datepicker({
                            // defaultDate: "+1w",
                            changeMonth: true,
                            numberOfMonths: 1
                        })
                        .on( "change", function() {
                            to.datepicker( "option", "minDate", getDate( this ) );
                        }),
                    to = $( "#date_2" ).datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        numberOfMonths: 1
                    })
                        .on( "change", function() {
                            from.datepicker( "option", "maxDate", getDate( this ) );
                        });

                function getDate( element ) {
                    var date;
                    try {
                        date = $.datepicker.parseDate( dateFormat, element.value );
                    } catch( error ) {
                        date = null;
                    }

                    return date;
                }
            });

            $( "#date_1" ).change(function(){


                var from_val = $.datepicker.formatDate('yy-mm-dd', new Date($("#date_1").val()));
                var to_val = '';
                if($("#date_2").val().length > 0){
                    to_val = $.datepicker.formatDate('yy-mm-dd', new Date($("#date_2").val()));
                }

                $("#from").val(from_val);
                $("#to").val(to_val);


                table.ajax.reload();
            });

            $( "#date_2" ).change(function(){
                var from_val = '';
                if($("#date_1").val().length > 0){
                    from_val = $.datepicker.formatDate('yy-mm-dd', new Date($("#date_1").val()));
                }
                var to_val = $.datepicker.formatDate('yy-mm-dd', new Date($("#date_2").val()));

                $("#from").val(from_val);
                $("#to").val(to_val);

                table.ajax.reload();
            });

            function disableDate(bool){
                $('#date_2').prop('disabled',bool);
                $('#date_1').prop('disabled',bool);
                if (bool) {
                    $('#date_2').css({"backgroundColor" : "#f5f5f5"});
                    $('#date_1').css({"backgroundColor" : "#f5f5f5"});
                } else {
                    $('#date_2').css({"backgroundColor" : "#ffffff"});
                    $('#date_1').css({"backgroundColor" : "#ffffff"});
                }
            }

            $( "#sort-bar a" ).click(function(){

                $( "#sort-bar a" ).css('text-decoration','none');

                $("#from").val('');
                $("#to").val('');
                $("#date_1").val('');
                $("#date_2").val('');

                if($(this).attr('id') == 'random'){
                    disableDate(false);
                }
                else if($(this).attr('id') == 'year'){
                    disableDate(true);
                    $("#range").val('year');
                    table.ajax.reload();
                }
                else if($(this).attr('id') == 'month'){
                    disableDate(true);
                    $("#range").val('month');
                    table.ajax.reload();
                }
                else if($(this).attr('id') == 'day'){
                    disableDate(true);
                    $("#range").val('day');
                    table.ajax.reload();
                }
                $(this).css('text-decoration','underline');
            });


            $(".dataTables_filter").prependTo($(".content_header__dp"));
            $(".dataTables_length").insertAfter($(".dataTable"));
            $(".dataTables_info").prependTo($(".sort-bar__left"));
            $("<img id='magnify_icon' src='/public/img/magnify.png'/>").appendTo($(".dataTables_filter"));
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