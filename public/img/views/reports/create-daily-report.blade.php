@extends('adminlte::page')

@section('title', 'Steko')

@section('content')
    <div class="container">
        <div class="row">
            @include('partials.form-status')

            {!! Form::open(array('action' => 'DailyReportController@store', 'method' => 'POST', 'role' => 'form')) !!}

            {!! csrf_field() !!}
            {!! Form::hidden('', Auth::user()->id, array('name'=>'user_id')) !!}

            <div class="box-body">
                <div class="form-group has-feedback">
                    <div class="form-dr dr_header">
                        <span>Ежедневный отчет</span>
                    </div>
                    <div class="form-group date_picker_input dr_header_date">
                        {!! Form::text('', old('date'), array('id' => 'datepicker', 'name'=>'date', 'class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <div class="form-group">
                        <div class="form-dr">
                            <span class="form-dr__label">{!! Form::label('', 'Звонки', array('class' => 'control-label')); !!}</span>
                        </div>
                        <div class="form-dr">
                            <p>{!! Form::label('count_in_calls', 'Количество входящих звонков', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('count_in_calls', NULL, array('id' => 'count_in_calls')) !!}
                        </div>
                        <div class="form-dr">
                            <p>{!! Form::label('count_out_calls', 'Количество исходящих звонков', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('count_out_calls', NULL, array('id' => 'count_out_calls')) !!}
                        </div>
                        <div class="form-dr">
                            <p>{!! Form::label('count_clients', 'Количество клиентов', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('count_clients', NULL, array('id' => 'count_clients')) !!}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group has-feedback">
                    <div class="form-group">
                        <div class="form-dr">
                            <span class="form-dr__label">{!! Form::label('', 'Просчеты', array('class' => 'control-label')); !!}</span>
                        </div>
                        <div class="form-dr">
                            <p>{!! Form::label('count_culations', 'Количество просчетов', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('count_culations', NULL, array('id' => 'count_culations')) !!}
                        </div>
                        <div class="form-dr">
                            <p>{!! Form::label('count_framework_culations', 'Количество конструкций в просчете', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('count_framework_culations', NULL, array('id' => 'count_framework_culations')) !!}
                        </div>
                        <div class="form-dr">
                            <p>{!! Form::label('common_culations', 'Общая сумма просчетов', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('common_culations', NULL, array('id' => 'common_culations')) !!}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group has-feedback">
                    <div class="form-group">
                        <div class="form-dr">
                            <span class="form-dr__label">{!! Form::label('', 'Счета', array('class' => 'control-label')); !!}</span>
                        </div>
                        <div class="form-dr">
                            <p>{!! Form::label('count_bills', 'Количество счетов', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('count_bills', NULL, array('id' => 'count_bills')) !!}
                        </div>
                        <div class="form-dr">
                            <p>{!! Form::label('count_framework_bills', 'Количество конструкций в счетах', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('count_framework_bills', NULL, array('id' => 'count_framework_bills')) !!}
                        </div>
                        <div class="form-dr">
                            <p>{!! Form::label('common_sum_bills', 'Общая сумма в счетах', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('common_sum_bills', NULL, array('id' => 'common_sum_bills')) !!}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group has-feedback">
                    <div class="form-group">
                        <div class="form-dr">
                            <span class="form-dr__label">{!! Form::label('', 'Оплаты', array('class' => 'control-label')); !!}</span>
                        </div>
                        <div class="form-dr">
                            <p>{!! Form::label('count_payments', 'Количество оплат', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('count_payments', NULL, array('id' => 'count_payments')) !!}
                        </div>
                        <div class="form-dr">
                            <p>{!! Form::label('count_framework_payments', 'Количество конструкций в оплатах', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('count_framework_payments', NULL, array('id' => 'count_framework_payments')) !!}
                        </div>
                        <div class="form-dr">
                            <p>{!! Form::label('common_sum_payments', 'Общая сумма в оплатах', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('common_sum_payments', NULL, array('id' => 'common_sum_payments')) !!}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group has-feedback">
                    <div class="form-group">
                        <div class="form-dr dr_last_input">
                            <p>{!! Form::label('count_done_leeds', 'Количество обработанных заявок', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('count_done_leeds', NULL, array('id' => 'count_done_leeds')) !!}
                        </div>
                        <div class="form-dr">
                            <p>{!! Form::label('direct_sample', 'Направленно на замер', array('class' => 'control-label')); !!}</p>
                            {!! Form::number('direct_sample', NULL, array('id' => 'direct_sample')) !!}
                        </div>
                        <div class="form-group congestion">
                            <div class="form-dr">
                                <span class="form-dr__label">{!! Form::label('', 'Прочее', array('class' => 'control-label')); !!}</span>
                            </div>
                            <div class="another">
                                <span id="label_another">{!! Form::label('', 'Оцените свою степень загруженности за день. 1 - совсем не загружен, 10 - максимально загружен', array('class' => 'control-label')); !!}</span>
                                @for($i=1;$i<=10;$i++)
                                    <div class="radio">
                                        <label>
                                            <input class="daily-rp-radio" type="radio" name="workload"
                                                   value="{{$i}}" checked="">
                                            {{$i}}
                                        </label>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                {!! Form::button('Сохранить', array('class' => 'btn center-block dr_save_btn','type' => 'submit', )) !!}

                {!! Form::close() !!}

                {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
        @stop


        @section('tmp_js')
            <script>
                $(function () {
                    $("#datepicker").datepicker({
                        // showOn: "button",
                        // buttonImage: "/img/calendar.gif",
                        // buttonImageOnly: true,
                        // buttonText: "Select date",
                        dateFormat: "yy-mm-dd"
                    });
                });
                $(".daily-rp-radio:checked").parent().css({"backgroundColor": "#00a3ff", "color": "#ffffff"});
                $(".daily-rp-radio").click(function () {
                    $(".daily-rp-radio").parent().css({"backgroundColor": "#ffffff", "color": "#00a3ff"});
                    $(".daily-rp-radio:checked").parent().css({"backgroundColor": "#00a3ff", "color": "#ffffff"});
                })
            </script>
@endsection