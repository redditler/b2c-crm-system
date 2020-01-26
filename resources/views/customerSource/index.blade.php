@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')

    <h1 class="content_header__h1">Источники клиентов</h1>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <button class="btn btn-info btn-sm" type="button" data-toggle="modal" data-target="#myModal">Добавить источник</button>
            <div id="myModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
                            <h4 class="modal-title">Создать источник</h4>
                        </div>
                        <div class="modal-body">
                            <form id="addSourcesForm">
                                {{csrf_field()}}
                                <label>Название источника
                                    <input type="text" class="form-control" name="name">
                                </label><br/>
                                <label>Alias источника
                                    <input type="text" class="form-control" name="alias">
                                </label><br/>
                                <label>Описание источника
                                    <textarea class="form-control" rows="7" style="width: 500px" name="description"></textarea>
                                </label>
                                <br/><input type="submit" id="addSources" class="btn btn-success" value="Добавить">
                            </form>
                        </div>
                        <div id="resultMessage"></div>
                        <div class="modal-footer">
                            <button class="btn btn-default" id="closeCreateSources" type="button" data-dismiss="modal">Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <table class="table table-striped table-bordered">
                <thead>
                <tr class="text-center">
                    <th>ID</th>
                    <th>NAME</th>
                    <th>ALIAS</th>
                    <th>DESCRIPTION</th>
                    <th>ACTION</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sources as $val)
                    <tr>
                        <td>{{$val->id}}</td>
                        <td>{{$val->name}}</td>
                        <td>{{$val->alias}}</td>
                        <td>{{$val->description}}</td>
                        <td>
                            <button class="btn btn-sm btn-success showChangeWindowSources" data-toggle="modal" data-target="#showEdit" value="{{$val->id}}">Изменить</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div id="showEdit" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Изменить источник</h4>
                </div>
                <div class="modal-body">
                    <form id="showEditSourcesForm">
                        {{csrf_field()}}
                        <div class="form-group">

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" id="closeEditSources" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('tmp_js')
    <script>
        $(document).ready(function () {

            $('#addSources').click(function (e) {
                e.preventDefault();
                $.ajax({
                    type:'post',
                    url:`{{route('addSources')}}`,
                    data:$('#addSourcesForm').serializeArray(),
                    success: function (result) {
                        $('#resultMessage').html(result);
                        setTimeout(function (){
                            $('#closeCreateSources').click();
                            $('#resultMessage').html('');
                            $('#addSourcesForm').trigger('reset');
                        }, 1000);

                    },
                });
            });

            $('.showChangeWindowSources').click(function (e) {
                e.preventDefault();

                $.ajax({
                   type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': `{{csrf_token()}}`,
                    },
                    url: `/show-edit-sources/${$(this).val()}`,
                    success:function (result) {
                       $('#showEditSourcesForm .form-group').html(`<input type="hidden" name="id" value="${result.id}"><label>Название источника
                                    <input type="text" class="form-control" name="name" value="${result.name}">
                                </label><br/><label>Alias источника
                                    <input type="text" class="form-control" name="alias" value="${result.alias}">
                                </label><br/>
                                <label>Описание источника
                                    <textarea class="form-control" rows="7" style="width: 500px" name="description">${result.description}</textarea>
                                </label>
                                <br/><input type="submit"  class="btn btn-success editSources" value="Изменить">`);

                       $('.editSources').click(function (e) {
                           e.preventDefault();
                           $.ajax({
                               type: 'post',
                               headers: {
                                   'X-CSRF-TOKEN': `{{csrf_token()}}`,
                               },
                               data: $('#showEditSourcesForm').serializeArray(),
                               url: `/edit-sources/${result.id}`,
                               success: function (resEdit) {
                                   $('.editSources').after(`<div class="editResoult">${resEdit}</div>`);
                                   setTimeout(function (){
                                       $('#closeEditSources').click();
                                       $('.editResoult').remove();
                                       $('#showEditSourcesForm').trigger('reset');
                                   }, 1000);
                                   console.log(resEdit);
                               }
                           });


                       });
                    }
                });


            });



        });

    </script>
@endsection