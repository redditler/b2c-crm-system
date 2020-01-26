@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <div class="leed-requests">
        <h1 class="content_header__h1">Добавить статус</h1>
        <a href="{{route('leadStatus.index')}}" class="btn btn-info">Вернутся</a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <form id="editLeadStatus">
                {{csrf_field()}}
                {{method_field('PUT')}}
                <div class="form-group">
                <label>Slug статуса
                <input type="text" name="slug" class="form-control" value="{{$leadStatus['slug']}}"  placeholder="Slug статуса" required></label>
                <label>Название статуса
                <input type="text" name="name" class="form-control" value="{{$leadStatus['name']}}"  placeholder="Название статуса" required></label>
                </div>
                <input type="submit" class="btn btn-success" value="Изменить">
            </form>
        </div>
    </div>
    <div class="alert alert-success" role="alert" style="display: none">
        <strong></strong>
    </div>
    <div class="alert alert-danger" role="alert" style="display: none">
        <strong></strong>
    </div>
@endsection

@section('tmp_js')
    <script>
        $(document).ready(function () {
            $('#editLeadStatus').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '{{route('leadStatus.update', ['leadStatus' => $leadStatus['id']])}}',
                    data: $(this).serializeArray(),
                    success:function (result) {
                        if (!Array.isArray(result)){
                            $('.alert-success').text(result);
                            setTimeout(function (){$('.alert-success').css('display', 'inline-block')}, 500);
                            setTimeout(function (){$('.alert-success').css('display', 'none')}, 2000);
                            setTimeout(function (){ window.location.href = '{{route('leadStatus.index')}}'}, 2000);
                        }else if(Array.isArray(result)){
                            $('.alert-danger').text(result);
                            setTimeout(function (){$('.alert-danger').css('display', 'inline-block')}, 1000);
                            setTimeout(function (){$('.alert-danger').css('display', 'none')}, 10000);

                        }
                    }
                });
            })

        });
    </script>
@endsection