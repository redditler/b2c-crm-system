@extends('adminlte::page')
@section('content')
    <div class="row">
        <h1>Квалификация клиента:</h1>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    @include('contact_quality.addContactQuality')
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-condensed" style="background-color: white">
                        <tr class="text-center">
                            <td>ID</td>
                            <td>TITLE</td>
                            <td>Description</td>
                            <td>Action</td>
                        </tr>
                        @foreach($contactQuality as $value)
                            <tr class="text-center">
                                <td style="width: 10%">{{$value->id}}</td>
                                <td style="width: 10%">{{$value->title}}</td>
                                <td class="text-left">{{$value->description}}</td>
                                <td style="width: 20%">
                                    <a href="#" class="btn btn-primary">Edit</a>
                                    <a href="#" class="btn btn-danger">X</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('tmp_js')
    <script>
        $(document).ready(function () {
            $('#addContactQualitySubmit').click(function (e) {
                e.preventDefault();

                $.ajax({
                    url:'{{route('addContactQuality')}}',
                    method: 'post',
                    data: $('#addContactQuality').serializeArray(),
                    success: function (result) {
                        alert(result);
                        $('#addContactQuality').trigger('reset');
                        $('.bs-addContactQuality-modal-lg').modal('hide');
                    }
                })
            })
        });

    </script>
@endsection

