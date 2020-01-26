@extends('adminlte::page')

@section('template_title')
@endsection

@section('users_css')
@endsection

@section('content')
    <div class="leed-requests">
        <h1 class="content_header__h1">Роли</h1>
    </div>
    <div class="row">
        <a href="{{route('roles.create')}}" class="btn btn-success">Create</a>
    </div>
   <div class="row">
       <div class="col-md-6">
           <table id="roles"></table>
       </div>
   </div>
@endsection

@section('tmp_js')
    <script>
        $(document).ready(function () {
            let roles = JSON.parse(`{!! $roles !!}`);

            $('#roles').DataTable({
                "scrollX": true,
                data: roles,
                columns: [
                    {title: "ID", data: 'id', name: 'id'},
                    {title: "Name", data: 'name', name:'name'},
                    {title: "Slug", data: 'slug', name: 'slug'},
                    {title: "Action", data:'id', render: function (data) {
                            return `<a href="roles/${data}/edit" class="btn btn-success">Edit</a>`
                        }}
                    ]
            });
        });
    </script>
@endsection