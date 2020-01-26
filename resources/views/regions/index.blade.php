@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <div class="leed-requests">
        <h1 class="content_header__h1">Регионы</h1>
        <a href="{{route('regions.create')}}" class="btn btn-success">Добавить</a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <table class="table table-bordered table-condensed text-center">
                <tr>
                    <td>ID</td>
                    <td>Name</td>
                    <td>Region_order</td>
                    <td>Status</td>
                    <td>API</td>
                    <td>Action</td>
                </tr>

                    @foreach($regions as $region)
                    <tr>
                        <td>{{$region['id']}}</td>
                        <td>{{$region['name']}}</td>
                        <td>{{$region['region_order']}}</td>
                        <td>{{$region['status']}}</td>
                        <td><input type="checkbox" name="api" class="regionApi" value="{{$region['id']}}" {{$region['api'] ? 'checked': ''}}></td>
                        <td><a href="{{route('regions.edit', ['region' => $region['id']])}}" class="btn btn-success">Редактировать</a></td>
                    </tr>
                    @endforeach
            </table>
        </div>
    </div>
@endsection
@section('tmp_js')
    <script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{@csrf_token()}}'
            }
        });

        $('.regionApi').change(function (e) {
            e.preventDefault();

            let api = $(this);
            $.ajax({
                method: 'post',
                url: '/changeApi',
                data: {
                    id: api.val(),
                    checked: api.is(':checked')
                },
                success: function (result) {
                    console.log(result)
                }
            });
        });


    })
    </script>
@endsection