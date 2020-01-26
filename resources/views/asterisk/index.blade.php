@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <h1 class="content_header__h1">Телефония</h1>
@stop

@section('content')

    <div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="addLeadCallModalClose" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridModalLabel">Создать лид</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid bd-example-row">
                        <div id="leadCreateResult"></div>
                        <form id="createLead">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="modal-content-createLead">
                                    <label><span>Имя</span>
                                        <input type="text" name="leed_name" class="form-control" required placeholder="Имя"></label>
                                    <label><span>Телефон</span>
                                        <input type="text" name="leed_phone" id="leadPhone" class="form-control" required
                                               placeholder="Телефон"></label>
                                    <label><span>Регион</span>
                                        <select class="form-control" name="leed_region_id" required>
                                            <option selected disabled>Выберите регион</option>
                                            @foreach(\App\Regions::query()->get() as $region)
                                                <option value="{{$region->id}}">{{$region->name}}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label><span>Тип заявки</span>
                                        <select class="form-control" name="label_id" required>
                                            <option selected disabled>Выберите тип</option>
                                            @foreach(\App\LeedLabel::query()->get() as $label)
                                                <option value="{{$label->id}}">{{$label->name}}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>

                                @if(\Illuminate\Support\Facades\Auth::user()->role_id != 5)
                                    <label class="comment-name-createlead">
                                        Комментарии
                                        <textarea type="text" name="comment" class="form-control"
                                                  ></textarea></label>
                                @else
                                    <label class="comment-name-createlead">Комментарии Call-Centre
                                        <textarea type="text" name="cm_comment" class="form-control"
                                                  ></textarea></label>
                                @endif
                                    <span style="color:#fff;font-size:8pt;">Enter для отправки формы, Shift+Enter для перевода строки.</span>
                            </div>
                            <input type="submit" id="submitLead" class="btn btn-success" value="Создать">
                        </form>
                    </div>
                </div>
                {{--<div class="modal-footer footer-for-modal-create-leeds">--}}
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal" id="leadCreateClose">Закрыть--}}
                    {{--</button>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
    {{--<div class="bd-example bd-example-padded-bottom">--}}
    {{--<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#gridSystemModal">--}}
    {{--Добавить лид--}}
    {{--</button>--}}
    {{--</div>--}}
    <div class="filter-asterisk-page">
        <form id="phoneFilter">
            <div class="form-group">
                <span class="date-search-form">Дата</span>
                <label>
                    <input type="date" class="form-control" id="dateIn" name="dateIn" placeholder="dateIn">
                </label>
                <label>
                    <input type="date" class="form-control" id="dateFrom" name="dateFrom" placeholder="dateFrom">
                </label>

            </div>
        </form>
        <label for="testSearch" class="search-in-asterisk">
            <span>Введите номер <i class="fa fa-search" style="padding-left: 7px"></i></span>
            <span id="testSearch"></span>
        </label>
    </div>

    <div class="col-md-12">
        <table class="table table-bordered text-center small" id="phoneInfo" data-source-num="{{ \Auth::user()->callog_num_list }}">
            <thead>
            <tr>
                <th>Дата (служебная)</th>
                <th>Дата</th>
                <th>Время</th>
                <th>Направление</th>
                <th>Ответил</th>
                <th>Результат</th>
                <th>Клиент</th>
                <th>Действие</th>
                <th>Статус клиента</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th style="display: none">Дата (служебная)</th>
                <th style="display: none">Дата</th>
                <th style="display: none">Время</th>
                <th style="display: none">Направление</th>
                <th style="display: none">Ответил</th>
                <th style="display: none">Результат</th>
                <th>Клиент</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <div id="notifications" style="position:fixed;bottom:25px;right:25px;"></div>
@endsection
@section('tmp_js')
    <script>
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            let phoneTable = $('#phoneInfo').DataTable({
                order: [[0, "desc"]],
                processing: false,
                serverSide: true,
                ajax: {
                    "url": '{!! route('phoneInfoTable')!!}',
                    "method": "POST",
                    'data': function (d) {
                        d.dateIn = $('#dateIn').val();
                        d.dateFrom = $('#dateFrom').val();
                    }
                },
                columns: [
                    {data: 'sortDate', name: 'sortDate', visible: false, type: 'num'},
                    {data: 'eventDate', name: 'eventDate', width: '7%'},
                    {data: 'eventTime', name: 'eventTime', width: '7%'},
                    {
                        data: 'direction', name: 'direction', render: function (data) {
                            if(data == "1"){
                                return `<span class="label label-success">Входящий</span>`;
                            }else if(data == "2"){
                                return `<span class="label label-info">Исходящий</span>`;
                            }else if(data == "3"){
                                return `<span class="label label-success">Входящий</span>`;
                            }
                        }
                    },
                    {data: 'answered', name: 'answered', render: function (data) {
                            if(Object.keys(data).length>1){
                                return `<span class="label label-info">${data['name']} <span class="label label-default">${data['num']}</span></span>`;
                            }else{
                                return data;
                            }
                        }
                    },
                    {
                        data: 'status', name: 'status', render: function (data) {
                            return `<span class="glyphicon glyphicon-${data ? 'ok text-green' : 'remove text-red'}" aria-hidden="true"></span>`;
                        }
                    },
                    {data: 'client', name: 'client', width: '20%'},
                    {
                        data: 'client', render: function (data) {
                            return `<div class="bd-example bd-example-padded-bottom">
                                        <button type="button" class="btn btn-success btn-sm copyLeadPhone" value="${data.substr(-10)}" title="Копировать номер">
                                            <span class="glyphicon glyphicon-copy"></span>
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm leadPhone" value="${data.substr(-10)}" data-toggle="modal" data-target="#gridSystemModal">Добавить лид</button>
                                    </div>`;
                        }
                    },
                    {data: 'clientStatus', name: 'clientStatus'},
                ],
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var input = document.createElement("input");
                        $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                    });
                },
                "drawCallback": function (settings) {
                    $('.leadPhone').on('click', function () {
                        $('#leadPhone').val($(this).val());
                    });
                },
                fixedHeader: {
                    header: true
                },
                language: {
                    "processing": "Обновление…",
                    "search": "",
                    "lengthMenu": "Показать по _MENU_ записей",
                    "info": "_TOTAL_ записей",
                    "infoEmpty": "Записи с 0 до 0 из 0 записей",
                    "infoFiltered": "(отфильтровано из _MAX_ записей)",
                    "infoPostFix": "",
                    "loadingRecords": "Загрузка записей…",
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
                order: [[0, 'desc']]
            });


            $('#testSearch').html($('#phoneInfo tfoot'));//переносим поиск телефона tfoot перед таблицей
            $('#phoneInfo_filter').css('display', 'none');//убераем кривой поиск

            $("#dateIn").change(function () {
                phoneTable.ajax.reload();
            });

            $("#dateFrom").change(function () {
                phoneTable.ajax.reload();
            });

            window.setInterval(function(){
                if(phoneTable.page.info().page == 0){
                    phoneTable.ajax.reload();
                }
            }, 5000);

            $('body').on('click', '.copyLeadPhone', function(event){
                var $tempElement = $('<input>');
                $('body').append($tempElement);
                $tempElement.val($(this).val()).select();
                document.execCommand("Copy");
                $tempElement.remove();
            });

            $('.form-control[name=comment],.form-control[name=cm_comment]').keypress(function (e) {
                if(e.which == 13 && !e.shiftKey) {
                    $('.form-control[name=comment],.form-control[name=cm_comment]')
                        .val($('.form-control[name=comment],.form-control[name=cm_comment]').val().replace(/[\r\n]+$/, ''));     
                    $('#submitLead').click();
                }
            });

            console.log($.Deferred().done(function () {
                phoneTable.change(function () {

                    $('.leadPhone').on('click', function () {
                        $('#leadPhone').val($(this).val());
                    });
                })
            }));

            $('#submitLead').click('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'post',
                    url: `{{route('createLead')}}`,
                    data: $('#createLead').serializeArray(),
                    success: function (result) {
                        if (!Array.isArray(result)) {
                            $('#leadCreateResult').html(result).addClass('alert').addClass('alert-success');
                            setTimeout(function () {
                                $('#addLeadCallModalClose').click();
                                $('#leadCreateResult').html('').removeClass('alert').removeClass('alert-danger').removeClass('alert-success');
                                $('#createLead').trigger('reset');
                            }, 1000);
                        } else if (Array.isArray(result)) {
                            $(result).each(function(thisIdx, thisInnerVal){
                                result[thisIdx] = '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> ' + thisInnerVal;
                            });
                            $('#leadCreateResult').html(result.join('<br>')).addClass('alert').addClass('alert-danger');
                        }
                    }
                });
            });

            function generateNotificationID(length) {
               var result           = '';
               var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
               var charactersLength = characters.length;
               for ( var i = 0; i < length; i++ ) {
                  result += characters.charAt(Math.floor(Math.random() * charactersLength));
               }
               return result;
            }

            function flushWebcallNotifications(notificationID){
                $('#webcallSuccess_' + notificationID).fadeOut();              $('#webcallSuccess_' + notificationID).remove();
                $('#webcallFailed_' + notificationID).fadeOut();               $('#webcallFailed_' + notificationID).remove();
            }

            $('body').on('click','.call-request',function(){
                var thisNotificationID = generateNotificationID(12);
                $('#notifications').append('\
    <div id="webcallInit_' + thisNotificationID + '" class="alert alert-info alert-dismissible" role="alert" style="display:none;">\
        <strong>Пожалуйста, подождите.</strong> Происходит инициализация виджета.\
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
            <span aria-hidden="true">&times;</span>\
        </button>\
    </div>');
                $('#webcallInit_' + thisNotificationID).fadeIn();
                var callRequestData = {
                    'source': $('#phoneInfo').attr('data-source-num'), 
                    'destination': $(this).attr('data-client-num')
                };
                $.ajax({
                    type: 'post',
                    url: '{{ route('phoneInfoRequestWebcall') }}',
                    data: callRequestData,
                    sourceData: callRequestData,
                    notificationID: thisNotificationID,
                    success: function (result) {
                        result = jQuery.parseJSON(result);
                        var resultMessage = 0;
                        if(typeof result.success !== "undefined"){
                            if(result.success == true){
                                resultMessage = 1;
                            }
                        }
                        $('#webcallInit_' + this.notificationID).fadeOut();
                        if(resultMessage == 1){
                            $('#notifications').append('\
    <div id="webcallSuccess_' + this.notificationID + '" class="alert alert-info alert-dismissible" role="alert" style="display:none;">\
        Совершается вызов с номера <strong>' + this.sourceData.source + '</strong> на <strong>' + this.sourceData.destination + '</strong>.\
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
            <span aria-hidden="true">&times;</span>\
        </button>\
    </div>');
                            $('#webcallSuccess_' + this.notificationID).fadeIn();
                        }else{
                            if(typeof result.error !== "undefined"){
                                $('#notifications').append('\
    <div id="webcallFailed_' + this.notificationID + '" class="alert alert-warning alert-dismissible" role="alert" style="display:none;">\
        Не удалось совершить вызов с номера <strong>' + this.sourceData.source + '</strong> на <strong>' + this.sourceData.destination + '</strong>:<br>' + result.error + '.\
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
            <span aria-hidden="true">&times;</span>\
        </button>\
    </div>');
                            }else{
                                $('#notifications').append('\
    <div id="webcallFailed_' + this.notificationID + '" class="alert alert-warning alert-dismissible" role="alert" style="display:none;">\
        Не удалось совершить вызов с номера <strong>' + this.sourceData.source + '</strong> на <strong>' + this.sourceData.destination + '</strong>.\
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
            <span aria-hidden="true">&times;</span>\
        </button>\
    </div>');
                            }
                            $('#webcallFailed_' + this.notificationID).fadeIn();
                        }
                        var forceNotificationID = this.notificationID;
                        setTimeout(function(){
                            flushWebcallNotifications(forceNotificationID);
                        }, 5000);
                    }
                });
            });

        });
    </script>

@endsection