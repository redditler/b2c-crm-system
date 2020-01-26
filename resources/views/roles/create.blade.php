@extends('adminlte::page')

@section('template_title')
@endsection

@section('users_css')
@endsection

@section('content')
    <div class="leed-requests">
        <h1 class="content_header__h1">Create role</h1>
        <a href="{{route('roles.index')}}" class="btn bg-primary"><span><icon class="glyphicon glyphicon-arrow-left"></icon></span> Back</a>
    </div>
    <div class="row">
        <div class="col-md-4">
            <form id="createRole">
                {{csrf_field()}}
                <lable>Name
                    <input type="text" class="form-control" name="name" placeholder="Name">
                </lable>
               <lable>Slug
                   <input type="text" class="form-control" name="slug" placeholder="Slug">
               </lable>
                <input type="submit" class="btn btn-success" value="Create">
            </form>
        </div>
    </div>
    <div class="alert alert-success" role="alert" style="display: none">
        <strong>Well done!</strong>
    </div>
    <div class="alert alert-danger" role="alert" style="display: none">
        <strong>Oh snap!</strong>
    </div>

@endsection

@section('tmp_js')
    <script>
        $(document).ready(function () {
           $('#createRole').on('submit', function (e) {
               e.preventDefault();
               $.ajax({
                   type: 'POST',
                   url: '{{route('roles.store')}}',
                   data: $(this).serializeArray(),
                   success:function (result) {
                        if (result == 'Role added!'){
                            setTimeout(function (){$('.alert-success').css('display', 'inline-block')}, 1000);
                            setTimeout(function (){$('.alert-success').css('display', 'none')}, 2000);
                            $('.alert-success').css('display', 'inline-block')
                            }else if(result != 'Role added!'){
                            setTimeout(function (){$('.alert-danger').css('display', 'inline-block')}, 1000);
                            setTimeout(function (){$('.alert-danger').css('display', 'none')}, 2000);

                       }
                   }
               });
           })

        });
    </script>
@endsection