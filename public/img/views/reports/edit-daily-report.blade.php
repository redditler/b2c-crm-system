@extends('adminlte::page')

@section('title', 'Steko')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">

                        Ежедневный отчет / Добавить отчет

                        <a href="/daily-reports" class="btn btn-info btn-xs pull-right">
                            <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                            <span class="hidden-sm hidden-xs">Назад</span>
                        </a>

                    </div>
                    <div class="panel-body">

                        @include('partials.form-status')

                        {!! Form::open(array('action' => array('DailyReportController@update',$daily_report->id), 'method' => 'PUT')) !!}

                        {!! csrf_field() !!}
                        {!! Form::hidden('', Auth::user()->id, array('name'=>'user_id')) !!}

                        <div class="box-body">
                            <div class="form-group has-feedback row">
                                <div class="col-md-2 pull-right">
                                    <div class="form-group">
                                        {!! Form::text('', $daily_report->date, array('id' => 'datepicker', 'name'=>'date', 'class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-feedback row">
                                <div class="form-group">
                                    {!! Form::label('', 'Звонки', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-3">
                                        {!! Form::label('count_in_calls', 'Количество входящих звонков', array('class' => 'control-label')); !!}
                                        {!! Form::text('count_in_calls', $daily_report->count_in_calls, array('id' => 'count_in_calls')) !!}
                                    </div>
                                    <div class="col-md-3">
                                        {!! Form::label('count_out_calls', 'Количество исходящих звонков', array('class' => 'control-label')); !!}
                                        {!! Form::text('count_out_calls', $daily_report->count_out_calls, array('id' => 'count_out_calls')) !!}
                                    </div>
                                    <div class="col-md-3">
                                        {!! Form::label('count_clients', 'Количество клиентов', array('class' => 'control-label')); !!}
                                        {!! Form::text('count_clients', $daily_report->count_clients, array('id' => 'count_clients')) !!}
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group has-feedback row">
                                <div class="form-group">
                                    {!! Form::label('', 'Просчеты', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-3">
                                        {!! Form::label('count_culations', 'Количество просчетов', array('class' => 'control-label')); !!}
                                        {!! Form::number('count_culations', $daily_report->count_culations, array('id' => 'count_culations')) !!}
                                    </div>
                                    <div class="col-md-3">
                                        {!! Form::label('count_framework_culations', 'Количество конструкций в просчете', array('class' => 'control-label')); !!}
                                        {!! Form::number('count_framework_culations', $daily_report->count_framework_culations, array('id' => 'count_framework_culations')) !!}
                                    </div>
                                    <div class="col-md-3">
                                        {!! Form::label('common_culations', 'Общая сумма просчетов', array('class' => 'control-label')); !!}
                                        {!! Form::text('common_culations', $daily_report->common_culations, array('id' => 'common_culations')) !!}
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group has-feedback row">
                                <div class="form-group">
                                    {!! Form::label('', 'Счета', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-3">
                                        {!! Form::label('count_bills', 'Количество счетов', array('class' => 'control-label')); !!}
                                        {!! Form::number('count_bills', $daily_report->count_bills, array('id' => 'count_bills')) !!}
                                    </div>
                                    <div class="col-md-3">
                                        {!! Form::label('count_framework_bills', 'Количество конструкций в счетах', array('class' => 'control-label')); !!}
                                        {!! Form::number('count_framework_bills', $daily_report->count_framework_bills, array('id' => 'count_framework_bills')) !!}
                                    </div>
                                    <div class="col-md-3">
                                        {!! Form::label('common_sum_bills', 'Общая сумма в счетах', array('class' => 'control-label')); !!}
                                        {!! Form::text('common_sum_bills', $daily_report->common_sum_bills, array('id' => 'common_sum_bills')) !!}
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group has-feedback row">
                                <div class="form-group">
                                    {!! Form::label('', 'Оплаты', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-3">
                                        {!! Form::label('count_payments', 'Количество оплат', array('class' => 'control-label')); !!}
                                        {!! Form::number('count_payments', $daily_report->count_payments, array('id' => 'count_payments')) !!}
                                    </div>
                                    <div class="col-md-3">
                                        {!! Form::label('count_framework_payments', 'Количество конструкций в оплатах', array('class' => 'control-label')); !!}
                                        {!! Form::number('count_framework_payments', $daily_report->count_framework_payments, array('id' => 'count_framework_payments')) !!}
                                    </div>
                                    <div class="col-md-3">
                                        {!! Form::label('common_sum_payments', 'Общая сумма в оплатах', array('class' => 'control-label')); !!}
                                        {!! Form::text('common_sum_payments', $daily_report->common_sum_payments, array('id' => 'common_sum_payments')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-feedback row">
                                <div class="form-group">
                                    <div class="col-md-3 col-lg-offset-3">
                                        {!! Form::label('count_done_leeds', 'Количество обработанных заявок', array('class' => 'control-label')); !!}
                                        {!! Form::text('count_done_leeds', $daily_report->count_done_leeds, array('id' => 'count_done_leeds')) !!}
                                    </div>
                                    <div class="col-md-3">
                                        {!! Form::label('direct_sample', 'Направленно на замер', array('class' => 'control-label')); !!}
                                        {!! Form::number('direct_sample', $daily_report->direct_sample, array('id' => 'direct_sample')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-feedback row">
                                <div class="form-group">
                                    {!! Form::label('', 'Прочее', array('class' => 'col-md-3 control-label')); !!}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {!! Form::label('', 'Оцените свою степень загруженности за день. 1 - совсем не загружен, 10 - максимально загружен', array('class' => 'control-label')); !!}
                                            @for($i=1;$i<=10;$i++)
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="workload"
                                                               value="{{$i}}" @if($i == $daily_report->workload) checked @endif>
                                                        {{$i}}
                                                    </label>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>


                        {!! Form::button('Сохранить', array('class' => 'btn btn-success btn-flat margin-bottom-1 center-block','type' => 'submit', )) !!}

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@section('tmp_js')
    <script>
        $(function () {
            $("#datepicker").datepicker({
                showOn: "button",
                buttonImage: "/img/calendar.gif",
                buttonImageOnly: true,
                buttonText: "Select date",
                dateFormat: "yy-mm-dd"
            });
        });
    </script>
@endsection