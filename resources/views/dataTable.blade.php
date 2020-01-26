{{--@extends('adminlte::page')--}}

{{--@section('title', 'Steko')--}}


{{--@section('content_header')--}}
    {{--<h1 class="header-stat">Все лиды</h1>--}}
{{--@stop--}}

{{--@section('content')--}}
    {{--<div class="container">--}}
        {{--<div class="row">--}}
            {{--<table id="leedAll" class="table cell-border display compact" style="width:100%">--}}
            {{--</table>--}}
        {{--</div>--}}
    {{--</div>--}}


{{--@endsection--}}

{{--@section('tmp_js')--}}
    {{--<script>--}}
        {{--$(document).ready(function () {--}}
            {{--let leeds = JSON.parse(`{!! $leeds !!}`);--}}
            {{--let regions = JSON.parse(`{!! $regions !!}`);--}}
            {{--let leedStatuses = JSON.parse(`{!! $leedStatuses !!}`);--}}


            {{--$('#leedAll').DataTable({--}}
                {{--"scrollX": true,--}}
                {{--data: leeds,--}}
                {{--columns: [--}}
                    {{--{title: "Дата", data: 'created_at', name: 'created_at'},--}}
                    {{--{title: "Регион", data: 'leed_region_id', render: function (data) {--}}
                            {{--return `${regions[data].name}`;--}}
                        {{--}--}}
                    {{--},--}}
                    {{--{title: "Имя", data: 'leed_name', name: 'leed_name'},--}}
                    {{--{title: "Телефон", data: 'leed_phone', name: 'leed_phone'},--}}
                    {{--{title: "Статус оброботки", data: 'status_id', render: function (data) {--}}
                            {{--if (data == 11) {--}}
                                {{--return `<div class="progress"><div class="progress-bar progress-bar-success" style="width: 25%"><span class="sr-only">25% Complete (success)</span></div></div>`;--}}
                            {{--} else if (data == 12) {--}}
                                {{--return `<div class="progress"><div class="progress-bar progress-bar-success" style="width: 50%"><span class="sr-only">50% Complete (success)</span></div></div>`;--}}
                            {{--} else if (data == 13) {--}}
                                {{--return `<div class="progress"><div class="progress-bar progress-bar-success" style="width: 75%"><span class="sr-only">75% Complete (success)</span></div></div>`;--}}
                            {{--} else if (data == 14) {--}}
                                {{--return `<div class="progress"><div class="progress-bar progress-bar-success" style="width: 100%"><span class="sr-only">100% Complete (success)</span></div></div>`;--}}
                            {{--} else if (data == 15) {--}}
                                {{--return `<div class="progress"><div class="progress-bar progress-bar-danger" style="width: 100%"><span class="sr-only">100% Complete (danger)</span></div></div>`;--}}
                            {{--} else {--}}
                                {{--return `<div class="progress"><div class="progress-bar progress-bar-warning" style="width: 0%"><span class="sr-only">0% Complete (warning)</span></div></div>`;--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                        {{--@if(\Illuminate\Support\Facades\Auth::user()->manager)--}}
                    {{--{title: "Статус", data: {status_id:'status_id',  id:'id'}, class:'status_id', render: function (data) {--}}
                            {{--return `<select class="form-control" form="${data.id}" name="status_id">--}}
                                        {{--<option selected value="${data.status_id}">${leedStatuses[data.status_id].name}</option>--}}
                                        {{--${data != 5 ? '<option  value="5">'+leedStatuses[5].name+'</option>' : ''}--}}
                                        {{--<option  value="11">${leedStatuses[11].name}</option>--}}
                                        {{--<option  value="12">${leedStatuses[12].name}</option>--}}
                                        {{--<option  value="13">${leedStatuses[13].name}</option>--}}
                                        {{--<option  value="14">${leedStatuses[14].name}</option>--}}
                                        {{--<option  value="15">${leedStatuses[15].name}</option>--}}
                                    {{--</select>`;--}}
                        {{--}--}}
                    {{--},--}}
                    {{--{--}}
                        {{--title: "Комментарий", data: {comment:'comment', id:'id'}, class:'comment', render: function (data) {--}}

                            {{--return `<input type="text" form="${data.id}" class="form-control" value="${data.comment ? data.comment : ''}" name="comment">`;--}}
                        {{--}--}}
                    {{--},--}}
                        {{--@else--}}
                    {{--{--}}
                        {{--title: "Статус", data: 'status_id', render: function (data) {--}}
                            {{--return `${leedStatuses[data].name}`;--}}
                        {{--}--}}
                    {{--},--}}
                    {{--{title: "Комментарий", data: 'comment', name: 'comment'},--}}
                        {{--@endif--}}
                        {{--@if(\Illuminate\Support\Facades\Auth::user()->manager)--}}
                    {{--{--}}
                        {{--title: "Действие", data: 'id', class:'id', render: function (data) {--}}
                            {{--return `<form  id="${data}">{{csrf_field()}}<input type="hidden" name="id" value="${data}"><input  type="submit" value="Применить" class="btn btn-success"></form>`;--}}
                        {{--}--}}
                    {{--}--}}
                        {{--@endif--}}
                {{--],--}}
                {{--language: {--}}
                    {{--"processing": "Подождите...",--}}
                    {{--"search": "",--}}
                    {{--"lengthMenu": "Показать по _MENU_ записей",--}}
                    {{--"info": "_TOTAL_ записей",--}}
                    {{--"infoEmpty": "Записи с 0 до 0 из 0 записей",--}}
                    {{--"infoFiltered": "(отфильтровано из _MAX_ записей)",--}}
                    {{--"infoPostFix": "",--}}
                    {{--"loadingRecords": "Загрузка записей...",--}}
                    {{--"zeroRecords": "Записи отсутствуют.",--}}
                    {{--"emptyTable": "В таблице отсутствуют данные",--}}
                    {{--"paginate": {--}}
                        {{--"first": "Первая",--}}
                        {{--"previous": "<",--}}
                        {{--"next": ">",--}}
                        {{--"last": "Последняя"--}}
                    {{--},--}}
                    {{--"aria": {--}}
                        {{--"sortAscending": ": активировать для сортировки столбца по возрастанию",--}}
                        {{--"sortDescending": ": активировать для сортировки столбца по убыванию"--}}
                    {{--}--}}
                {{--}--}}
            {{--});--}}
           {{--//--}}
            {{--let form = $('[name=id]').serializeArray();--}}

            {{--for (var prop in form) {--}}
                {{--//console.log(form[prop].value);--}}
               {{--console.log($('#'+form[prop].value).serializeArray());--}}
                {{--$('#'+form[prop].value).on('submit', function (e) {--}}
                    {{--e.preventDefault();--}}
                    {{--$.ajax({--}}
                        {{--type: 'POST',--}}
                        {{--url: `/leedsUpdate/${form[prop].value}`,--}}
                        {{--data:  $('#'+form[prop].value).serializeArray(),--}}
                        {{--success:function (result) {--}}
                            {{--alert(result);--}}
                        {{--},--}}
                    {{--});--}}
                {{--});--}}
            {{--}--}}


        {{--});--}}
    {{--</script>--}}
{{--@endsection--}}