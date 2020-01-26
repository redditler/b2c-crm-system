@extends('adminlte::page')

@section('title', 'Steko')

@section('content')
    <div class="container show-contacts__header">
        <div class="row">
            <a href="/contacts">Назад</a>
        </div>
        <div class="row">
            Контакты / {{$contact->fio}}
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <span class="show-contacts_label">ФИО</span>
                <p class="show-contacts_info">{{$contact->fio}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <span class="show-contacts_label">Область</span>
                <p class="show-contacts_info">
                    @if(!empty($contact->region_id))
                        {{$contact->regions->name}}
                    @endif
                </p>
            </div>
            <div class="col-sm-3">
                <span class="show-contacts_label">Город</span>
                <p class="show-contacts_info">
                    @if(!empty($contact->city))
                        {{$contact->city}}
                    @endif
                </p>
            </div>
        </div>
        <div class="row">
            @foreach($contact->phones AS $phone)
                <div class="col-sm-3">
                    <span class="show-contacts_label">Телефон</span>
                    <p class="show-contacts_info">
                        {{$phone->phone}}
                    </p>
                </div>
            @endforeach
            <div class="col-sm-3">
                <span class="show-contacts_label">E-mail</span>
                <p class="show-contacts_info"><strong>
                        @if(!empty($contact->email))
                            {{$contact->email}}
                        @endif
                    </strong></p>
            </div>
            <div class="col-sm-3">
                @if(!empty($contact->diler))
                    <div class="diller-steko">
                        Дилер Steko
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <span class="show-contacts_label">Ответственный менеджер</span>
                <p class="show-contacts_info">
                    @if(!empty($contact->user_id))
                        {{$contact->manager->name}}
                    @endif
                </p>
            </div>
            <div class="col-sm-3">
                <span class="show-contacts_label">Подразделение</span>
                <p class="show-contacts_info">
                    @if(!empty($contact->group_id))
                        {{$contact->group->name}}
                    @endif
                </p>
            </div>
        </div>
    </div>
    <div class="row bord-top">
        <div class="col-md-12">
            <span class="show-contacts__table-label">История обращений</span>
        </div>
        <div class="col-md-12">
            <table id="history-table" class="display responsive no-wrap" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>ФИО</td>
                    <td>Город</td>
                    <td>Менеджер</td>
                    <td>Телефон</td>
                    <td>Последние обращение</td>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <br>
    <br>
    <br>
    <div class="show-cont">
        <div class="button-bottom">
            {!! Form::open(array('url' => 'contacts/' . $contact->id, 'class' => '')) !!}
            {!! Form::open(array('route' => array('contacts.destroy',$contact->id), 'method' => 'POST', 'role' => 'form')) !!}
            {!! Form::hidden('_method', 'DELETE') !!}
            {!! Form::button('Удалить контакт', array('class' => 'btn show-contacts__delete','type' => 'submit')) !!}
            {!! Form::close() !!}
        </div>
        <div class="button-bottom"><a href="#" class="btn show-contacts__group">Обьеденить контакт</a></div>
        <div class="button-bottom"><a href="/contacts/{{$contact->id}}/edit" class="btn show-contacts__edit"> Изменить контакт</a></div>
    </div>




    {{--<div class="row">--}}
    {{--<div class="col-md-4 text-center">--}}
    {{--{!! Form::open(array('url' => 'contacts/' . $contact->id, 'class' => '')) !!}--}}
    {{--{!! Form::open(array('route' => array('contacts.destroy',$contact->id), 'method' => 'POST', 'role' => 'form')) !!}--}}
    {{--{!! Form::hidden('_method', 'DELETE') !!}--}}
    {{--{!! Form::button('Удалить контакт', array('class' => 'btn show-contacts__delete','type' => 'submit')) !!}--}}
    {{--{!! Form::close() !!}--}}
    {{--</div>--}}
    {{--<div class="col-md-4 text-center"><a href="#" class="btn show-contacts__group">Обьеденить--}}
    {{--контакт</a></div>--}}
    {{--<div class="col-md-4"><a href="/contacts/union/{{$contact->id}}" class="btn btn-block btn-sm">Обьеденить--}}
    {{--контакт</a></div>--}}
    {{--<div class="col-md-4 text-center"><a href="/contacts/{{$contact->id}}/edit" class="btn show-contacts__edit"> Изменить--}}
    {{--контакт</a></div>--}}
    {{--</div>--}}
@stop


@section('tmp_js')
    <script>
        table = null;
        $(document).ready(function () {
            var table = $('#history-table').DataTable({
                order: [[4, "desc"]],
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "<?php echo route('get-history-leeds') ?>",
                    "method": "POST",
                    "data": function (d) {

                        d._token = $("input[name='_token']").val();
                        d.phones = '{{$contact->phones->pluck('phone')->toJson()}}';

                    }
                },
                columns: [
                    {data: 'leed_name', name: 'leed_name', orderable: false, searchable: false},
                    {data: 'region', name: 'region', orderable: false, searchable: false},
                    {data: 'manager', name: 'manager', orderable: false, searchable: false},
                    {data: 'leed_phone', name: 'leed_phone', orderable: false, searchable: false},
                    {data: 'created_at', name: 'created_at', searchable: false}
                ],
                language: {
                    "processing": "Подождите...",
                    "search": "Поиск:",
                    "lengthMenu": "Показать _MENU_ записей",
                    "info": " ",
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

            $('#history-table_filter').hide();
            $(".dataTables_length").insertAfter($(".dataTable"));
            $(".dataTables_info").hide();
        });
    </script>
@endsection