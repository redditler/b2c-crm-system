@extends('adminlte::page')

@section('title', 'Steko')


@section('content_header')
    <h1 class="content_header__h1" style="width: 56%;">Ежедневный отчет</h1>
@stop

@section('content')
    <form id="createReport">
        {{csrf_field()}}
        {{method_field('PUT')}}
        <div class="first-column-for-createReport">
            <div class="item-from-createReport">
                <div class="head-name-form-item-createReport">
                    <span>Звонки</span>
                </div>
                <div class="body-form-item-createReport">
                    <div class="row-from-body">
                        <div class="left-section-from-row">
                            <label for="count_out_calls" class="control-label">Количество исходящие звонки(уникальных клиентов)</label>
                        </div>
                        <div class="right-section-from-row">
                            <input id="count_out_calls" name="count_out_calls" value="{{$report['count_out_calls']}}" type="number" required>
                        </div>
                    </div>
                    <div class="row-from-body">
                        <div class="left-section-from-row">
                            <label for="count_in_calls" class="control-label">Количество входящих звонков(уникальных клиентов)</label>
                        </div>
                        <div class="right-section-from-row">
                            <input id="count_in_calls" name="count_in_calls" value="{{$report['count_in_calls']}}" type="number" required>
                        </div>
                    </div>
                    <div class="row-from-body">
                        <div class="left-section-from-row">
                            <label for="count_done_leeds" class="control-label">Количество обработанных лидов</label>
                        </div>
                        <div class="right-section-from-row">
                            <input id="count_done_leeds" name="count_done_leeds"  value="{{$report['count_done_leeds']}}" type="number" required>
                        </div>
                    </div>
                    <div class="row-from-body">
                        <div class="left-section-from-row">
                            <label for="count_clients" class="control-label">Посетители</label>
                        </div>
                        <div class="right-section-from-row">
                            <input id="count_clients" name="count_clients" type="number" value="{{$report['count_clients']}}" required>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="item-from-createReport">--}}
                {{--<div class="head-name-form-item-createReport">--}}
                    {{--<span>Просчеты и замеры</span>--}}
                {{--</div>--}}
                {{--<div class="body-form-item-createReport">--}}
                    {{--<div class="row-from-body">--}}
                        {{--<div class="left-section-from-row">--}}
                            {{--<label for="count_culations" class="control-label">Количество просчитанных конструкций в шт.</label>--}}
                        {{--</div>--}}
                        {{--<div class="right-section-from-row">--}}
                            {{--<input id="count_culations" name="count_culations" value="{{$report['count_culations']}}" type="number" required>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row-from-body">--}}
                        {{--<div class="left-section-from-row">--}}
                            {{--<label for="common_culations" class="control-label">Общая сумма просчетов</label>--}}
                        {{--</div>--}}
                        {{--<div class="right-section-from-row">--}}
                            {{--<input id="common_culations" name="common_culations" value="{{$report['common_culations']}}" type="number" required>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row-from-body">--}}
                        {{--<div class="left-section-from-row">--}}
                            {{--<label for="direct_sample" class="control-label">Направленно на замер</label>--}}
                        {{--</div>--}}
                        {{--<div class="right-section-from-row">--}}
                            {{--<input id="direct_sample" name="direct_sample" value="{{$report['direct_sample']}}" type="number" required>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row-from-body">--}}
                        {{--<div class="left-section-from-row">--}}
                            {{--<label for="count_framework_culations" class="control-label">Количество конструкций в замерах</label>--}}
                        {{--</div>--}}
                        {{--<div class="right-section-from-row">--}}
                            {{--<input id="count_framework_culations" name="count_framework_culations" value="{{$report['count_framework_culations']}}" type="number" required>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
        <div class="second-column-for-createReport">
            {{--<div class="item-from-createReport">--}}
                {{--<div class="head-name-form-item-createReport">--}}
                    {{--<span>Счета</span>--}}
                {{--</div>--}}
                {{--<div class="body-form-item-createReport">--}}
                    {{--<div class="row-from-body">--}}
                        {{--<div class="left-section-from-row">--}}
                            {{--<label for="count_bills" class="control-label">Количество выставленных счетов</label>--}}
                        {{--</div>--}}
                        {{--<div class="right-section-from-row">--}}
                            {{--<input id="count_bills" name="count_bills" value="{{$report['count_bills']}}" type="number" required>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row-from-body">--}}
                        {{--<div class="left-section-from-row">--}}
                            {{--<label for="count_framework_bills" class="control-label">Количество конструкций в счетах</label>--}}
                        {{--</div>--}}
                        {{--<div class="right-section-from-row">--}}
                            {{--<input id="count_framework_bills" name="count_framework_bills" value="{{$report['count_framework_bills']}}" type="number" required>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row-from-body">--}}
                        {{--<div class="left-section-from-row">--}}
                            {{--<label for="common_sum_bills" class="control-label">Общая сумма в счетах</label>--}}
                        {{--</div>--}}
                        {{--<div class="right-section-from-row">--}}
                            {{--<input id="common_sum_bills" name="common_sum_bills" value="{{$report['common_sum_bills']}}" type="number" required>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="item-from-createReport">
                <div class="head-name-form-item-createReport">
                    <span>Оплаты</span>
                </div>
                <div class="body-form-item-createReport">
                    <div class="row-from-body">
                        <div class="left-section-from-row">
                            <label for="count_payments" class="control-label">Количество оплат</label>
                        </div>
                        <div class="right-section-from-row">
                            <input id="count_payments" name="count_payments" value="{{$report['count_payments']}}" type="number" required>
                        </div>
                    </div>
                    <div class="row-from-body">
                        <div class="left-section-from-row">
                            <label for="count_framework_payments" class="control-label">Количество конструкций в оплатах</label>
                        </div>
                        <div class="right-section-from-row">
                            <input id="count_framework_payments" name="count_framework_payments" value="{{$report['count_framework_payments']}}" type="number" required>
                        </div>
                    </div>
                    <div class="row-from-body">
                        <div class="left-section-from-row">
                            <label for="common_sum_payments" class="control-label">Общая сумма в оплатах</label>
                        </div>
                        <div class="right-section-from-row">
                            <input id="common_sum_payments" name="common_sum_payments" value="{{$report['common_sum_payments']}}" type="number" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="submit" value="Сохранить" class="create-new-contact-bt">
        <div id="errors"></div>
    </form>




    {{--<div class="container-fluid">--}}
    {{--<div class="row">--}}
    {{--<form id="createReport">--}}
    {{--{{csrf_field()}}--}}
    {{--{{method_field('PUT')}}--}}
    {{--<div class="row">--}}
    {{--<div class="form_report">--}}
    {{--<div class="form-group has-feedback">--}}
    {{--<div class="form-group form_block">--}}
    {{--<div class="form_group_title">--}}
    {{--<div class="form-dr">--}}
    {{--<span class="form-dr__label">--}}
    {{--<label for="" class="control-label">Звонки</label></span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form_group_inputs">--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="count_clients" class="control-label">Посетители</label></p>--}}
    {{--<input id="count_clients" name="count_clients" type="number" value="{{$report['count_clients']}}" required>--}}
    {{--</div>--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="count_done_leeds" class="control-label">Количество обработанных лидов</label></p>--}}
    {{--<input id="count_done_leeds" name="count_done_leeds"  value="{{$report['count_done_leeds']}}" type="number" required>--}}
    {{--</div>--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="count_in_calls" class="control-label">Количество входящих звонков(уникальных клиентов)</label></p>--}}
    {{--<input id="count_in_calls" name="count_in_calls" value="{{$report['count_in_calls']}}" type="number" required>--}}
    {{--</div>--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="count_out_calls" class="control-label">Количество исходящие звонки(уникальных клиентов)</label></p>--}}
    {{--<input id="count_out_calls" name="count_out_calls" value="{{$report['count_out_calls']}}" type="number" required>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form-group has-feedback">--}}
    {{--<div class="form-group form_block">--}}
    {{--<div class="form_group_title">--}}
    {{--<div class="form-dr">--}}
    {{--<span class="form-dr__label"><label for="" class="control-label">Просчеты и замеры</label></span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form_group_inputs">--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="count_culations" class="control-label">Количество просчитанных конструкций в шт.</label></p>--}}
    {{--<input id="count_culations" name="count_culations" value="{{$report['count_culations']}}" type="number" required>--}}
    {{--</div>--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="common_culations" class="control-label">Общая сумма просчетов</label></p>--}}
    {{--<input id="common_culations" name="common_culations" value="{{$report['common_culations']}}" type="number" required>--}}
    {{--</div>--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="direct_sample" class="control-label">Направленно на замер</label></p>--}}
    {{--<input id="direct_sample" name="direct_sample" value="{{$report['direct_sample']}}" type="number" required>--}}
    {{--</div>--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="count_framework_culations" class="control-label">Количество конструкций в замерах</label></p>--}}
    {{--<input id="count_framework_culations" name="count_framework_culations" value="{{$report['count_framework_culations']}}" type="number" required>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form-group has-feedback">--}}
    {{--<div class="form-group form_block">--}}
    {{--<div class="form_group_title">--}}
    {{--<div class="form-dr">--}}
    {{--<span class="form-dr__label"><label for="" class="control-label">Счета</label></span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form_group_inputs">--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="count_bills" class="control-label">Количество выставленных счетов</label></p>--}}
    {{--<input id="count_bills" name="count_bills" value="{{$report['count_bills']}}" type="number" required>--}}
    {{--</div>--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="count_framework_bills" class="control-label">Количество конструкций в счетах</label></p>--}}
    {{--<input id="count_framework_bills" name="count_framework_bills" value="{{$report['count_framework_bills']}}" type="number" required>--}}
    {{--</div>--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="common_sum_bills" class="control-label">Общая сумма в счетах</label></p>--}}
    {{--<input id="common_sum_bills" name="common_sum_bills" value="{{$report['common_sum_bills']}}" type="number" required>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form-group has-feedback">--}}
    {{--<div class="form-group form_block">--}}
    {{--<div class="form_group_title">--}}
    {{--<div class="form-dr">--}}
    {{--<span class="form-dr__label"><label for="" class="control-label">Оплаты</label></span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form_group_inputs">--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="count_payments" class="control-label">Количество оплат</label></p>--}}
    {{--<input id="count_payments" name="count_payments" value="{{$report['count_payments']}}" type="number" required>--}}
    {{--</div>--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="count_framework_payments" class="control-label">Количество конструкций в оплатах</label></p>--}}
    {{--<input id="count_framework_payments" name="count_framework_payments" value="{{$report['count_framework_payments']}}" type="number" required>--}}
    {{--</div>--}}
    {{--<div class="form-dr form_input">--}}
    {{--<p><label for="common_sum_payments" class="control-label">Общая сумма в оплатах</label></p>--}}
    {{--<input id="common_sum_payments" name="common_sum_payments" value="{{$report['common_sum_payments']}}" type="number" required>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<input type="submit" value="Сохранить" class="btn btn-default">--}}
    {{--</form>--}}
    {{--<div id="errors"></div>--}}
    {{--</div>--}}
    {{--</div>--}}
@endsection

@section('tmp_js')
    <script>
        $(document).ready(function () {
            $('#createReport').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'PUT',
                    url: '{{route('daily-reports.update', ['daily_report' => $id])}}',
                    data: $('#createReport').serializeArray(),
                    success:function (result) {
                        if (Array.isArray(result)){
                            $('.errors').remove();
                            $.each(result, function (i, item) {
                                $('#errors').after(`<div class="alert alert-danger text-center errors"><h3>${item}</h3></div>`);
                            });
                        }else{
                            $('.errors').remove();
                            $('#errors').after(`<div class="alert alert-success text-center errors"><h2>${result}</h2></div>`);
                            window.location.href = '{{route('daily-reports.index')}}'
                        }
                    }
                });
            });
        });
    </script>
@endsection