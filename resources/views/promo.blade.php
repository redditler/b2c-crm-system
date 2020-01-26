@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <h1>Промокоды</h1>
@stop

@section('content')
    <div class="sort-bar">
        {!! Form::text('range', '', array('id' => 'range', 'hidden')); !!}
        <div class="sort-bar__left">
            <a href="javascript:void(0);" style="display: inline-block" id="all_request"></a>
        </div>
    </div>

    <br>
    <div class="row">
        {!! Form::label('region_id', 'Фильтр по городу' , array('class' => 'col-md-2')); !!}
        <div class="col-md-2">
            <div class="input-group">
                {!! Form::select('region_id', $regions, null, array('id' => 'region_id', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table id="leeds-promo-table" class="display responsive no-wrap leed-requests " cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <input type="text"
                               name="ps_name"
                               id="ps_name"
                               class="table-search-input">
                    </td>
                    <td>
                        <input type="text"
                               name="ps_phone"
                               id="ps_phone"
                               class="table-search-input">
                    </td>
                    <td>
                        <input type="text"
                               name="ps_email"
                               id="ps_email"
                               class="table-search-input">
                    </td>
                    <td>
                        <input type="text"
                               name="ps_promo_code"
                               id="ps_promo_code"
                               class="table-search-input">
                    </td>
                    <td>
                        <input type="text"
                               name="ps_promo_discount"
                               id="ps_promo_discount"
                               class="table-search-input">
                    </td>
                    <td></td>
                    <td></td>
                    <?php if(Auth::user()->manager): ?>
                    <td></td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <td>Время входа</td>
                    <td>Регион</td>
                    <td>Имя</td>
                    <td>Телефон</td>
                    <td>Email</td>
                    <td>Промокод</td>
                    <td>Скидка</td>
                    <td>Статус</td>
                    <td>Комментарий</td>
                    <?php if(Auth::user()->manager): ?>
                    <td>Действие</td>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@stop


@section('tmp_js')
    <script>
        table = null;
        $(document).ready(function () {
            var table = $('#leeds-promo-table').DataTable({
                // order: [[ 0, "desc" ]],
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    "url": "<?php echo route('get-promo-leeds') ?>",
                    "method": "POST",
                    "data": function (d) {

                        d._token = $("input[name='_token']").val();
                        d.region_id = $("#region_id").val();
                        d.date_from = $("#from").val();
                        d.date_to = $("#to").val();
                        d.date_range = "day";
                        d.ps_name = $("#ps_name").val();
                        d.ps_phone = $("#ps_phone").val();
                        d.ps_email = $("#ps_email").val();
                        d.ps_promo_code = $("#ps_promo_code").val();
                        d.ps_promo_discount = $("#ps_promo_discount").val();
                    }
                },
                columns: [
                    // {data: 'id', name: 'id', width: "5%"},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'region', name: 'region'},
                    {data: 'leed_name', name: 'leed_name'},
                    {data: 'leed_phone', name: 'leed_phone', orderable: false},
                    {data: 'promo_email', name: 'promo_email', searchable: true},
                    // {data: 'managers', name: 'managers', orderable: false, searchable: false},
                    {data: 'promo_code', name: 'promo_code', orderable: false, searchable: true},
                    {data: 'promo_discount', name: 'promo_discount', orderable: false, searchable: true},
                    {data: 'statuses', name: 'statuses', orderable: false, searchable: false},
                    {data: 'comment', name: 'comment', orderable: false},
                        <?php if(Auth::user()->manager): ?>
                    {
                        data: 'btns', name: 'btns', orderable: false, searchable: false
                    }
                    <?php endif; ?>
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
            $("#leeds-promo-table_filter").prependTo($(".content_header__dp"));
            $("#leeds-promo-table_length").insertAfter($("#leeds-promo-table"));
            $("#leeds-promo-table_info").prependTo($("#all_request"));
            $("<img id='magnify_icon' src='/img/magnify.png'/>").appendTo($("#leeds-promo-table_filter"));

            //Обновление таблицы datatable
            setInterval(function () {
                table.ajax.reload(null, false);
            }, 60000);

            $('#leeds-promo-table tbody').on('click', 'button', function () {
                var data = table.row($(this).parents('tr')).data();
                var leed_id = data['leed_id'];
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
            $("#leeds-promo-table_filter input").focusin(function () {
                $("#leeds-promo-table_filter").animate({
                    "width": "170px"
                });

            });
            $("#leeds-promo-table_filter input").focusout(function () {
                $("#leeds-promo-table_filter").animate({
                    "width": "40px"
                });
            });
            $("#magnify_icon").click(function () {
                if ($("#leeds-promo-table_filter input").width() == 36) {
                    $("#leeds-promo-table_filter input").focus();
                } else {
                    $("#leeds-promo-table_filter input").val(" ");
                }

            });

            $("#region_id").change(function () {
                table.ajax.reload();
            });

            // Search by name
            $("#ps_name").on('keyup', function () {
                table.ajax.reload();
            });
            $("#ps_name").on('change', function () {
                $("#ps_name").val('');
                table.ajax.reload();
            });

            // Search by phone
            $("#ps_phone").on('keyup', function () {
                table.ajax.reload();
            });
            $("#ps_phone").on('change', function () {
                $("#ps_phone").val('');
                table.ajax.reload();
            });

            // Search by email
            $("#ps_email").on('keyup', function () {
                table.ajax.reload();
            });
            $("#ps_email").on('change', function () {
                $("#ps_email").val('');
                table.ajax.reload();
            });

            // Search by promo_code
            $("#ps_promo_code").on('keyup', function () {
                table.ajax.reload();
            });
            $("#ps_promo_code").on('change', function () {
                $("#ps_promo_code").val('');
                table.ajax.reload();
            });

            // Search by promo_discount
            $("#ps_promo_discount").on('keyup', function () {
                table.ajax.reload();
            });
            $("#ps_promo_discount").on('change', function () {
                $("#ps_promo_discount").val('');
                table.ajax.reload();
            });


        });
    </script>

@endsection
