@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <div class="leed-requests">
        <h1 class="content_header__h1">Добавить статус для лида</h1>
        <a href="{{route('leadStatus.index')}}" class="btn btn-info">Вернутся</a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <form id="createRegion">
                {{csrf_field()}}
                <div class="form-group">
                <label>Название статуса
                <input type="text" name="name" class="form-control"  placeholder="Введите название" required></label>
                <label>Slug
                <input type="text" name="slug" class="form-control"  placeholder="Введите slug" required></label>
                </div>
                <input type="submit" class="btn btn-success" value="Добавить">
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
            $('#createRegion').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{route('leadStatus.store')}}',
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