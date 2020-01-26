@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <h1>TEST</h1>
@stop

@section('content')

@php
    use App\Support\LeadFilter\LeadFilterRender;use App\User;use App\UserGroups;use App\UserRm;


    $salon_id = ['1', '2', '5','7', '10'];
    $request = new stdClass();
    $request->salon_id = $salon_id;

    $test =  User::getWorkUser()
            ->where(function ($q) use($request){
                foreach ($request->salon_id as $salon){
                    $q->orWhere('branch_id', $salon);
                }
            })
            ->get();

    dd($test);



@endphp

@stop


@section('tmp_js')
{{--    <script src="{{asset('js/support/contactsXls.js')}}"></script>--}}

    <script>
        $(document).ready(function () {

          $('#checkboxGoogle2fa').change(function (e) {
              console.log($('#checkboxGoogle2fa').is(':checked'))
              });
          });

    </script>
@endsection