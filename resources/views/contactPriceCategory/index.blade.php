@extends('adminlte::page')

@section('content_header')
    <div class="title-page">
        <h1 class="title-page__name">Ценовой сегмент клиентов</h1>
    </div>
@endsection

@section('content')
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-2">
                <a href="{{route('contactPriceCategory.create')}}" class="btn btn-primary">Create</a>
            </div>
        </div>
        <div class="row">

            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                    <tr class="text-center">
                        <td>ID</td>
                        <td>Name</td>
                        <td>Slug</td>
                        <td>Description</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($priceCategory as $category)
                        @if(!empty($category))
                            <tr>
                                <td>{{$category->id}}</td>
                                <td>{{$category->name}}</td>
                                <td>{{$category->slug}}</td>
                                <td>{{$category->description}}</td>
                                <td>
                                    <a href="{{route('contactPriceCategory.edit', ['{contactPriceCategory' =>$category->id ])}}"
                                       class="btn btn-info">Edit</a></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('tmp_js')

@endsection

