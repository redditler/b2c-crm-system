@extends('adminlte::page')

@section('content_header')
    <div class="title-page">
        <h1 class="title-page__name">Ценовой сегмент клиентов</h1>
    </div>
@endsection

@section('content')
    <div class="col-md-4">
        <form action="{{route('contactPriceCategory.update', ['contactPriceCategory' => $category->id])}}" method="post">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="name" value="{{$category->name}}">
                <label for="slug">Slug</label>
                <input type="text" class="form-control" name="slug" id="slug" placeholder="slug" value="{{$category->slug}}">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" rows="6">{{$category->description}}</textarea>
            </div>
            <div class="form-group">
                <input type="submit" class="form-control btn btn-success" value="Submit">
            </div>
        </form>
    </div>
@endsection

@section('tmp_js')

@endsection

