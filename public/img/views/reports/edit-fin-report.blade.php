@extends('adminlte::page')

@section('title', 'Steko')

@section('content')
    <div class="container">
        <h1 class="fin-rep-header">Финансовый отчет</h1>
        <div class="panel panel-default" id="form-fr-panel">
            <div class="panel-body">
                @include('partials.form-status')

                {!! Form::open(array('action' => array('FinPlanController@update',$fin_report->id), 'method' => 'PUT')) !!}

                {!! csrf_field() !!}

                <div class="box-body">
                    <div id="reports">
                        <div class="report">
                            {!! Form::hidden('', $fin_report->user_id, array('name'=>'user_id')) !!}
                            {!! Form::hidden('', $fin_report->branch_id, array('name'=>'branch_id')) !!}
                            <div class="form-group has-feedback">
                                <div class="form-group date_picker_input dr_header_date" id="fr-date">
                                    {!! Form::text('', $fin_report->date, array('class' => 'datepicker', 'name'=>'date')) !!}
                                </div>
                            </div>
                            <div class="form-group has-feedback">
                                <div class="form-group" >
                                    <div class="form-fr">
                                        <span class="form-fr__label">{!! Form::label('', 'Заказ', array('class' => 'control-label')); !!}</span>
                                    </div>
                                    <div class="form-fr">
                                        <p>{!! Form::label('num_order', '№ заказа', array('class' => 'control-label')); !!}</p>
                                        {!! Form::text('num_order', $fin_report->num_order, array('name' => 'num_order')) !!}
                                    </div>
                                    <div class="form-fr">
                                        <p>{!! Form::label('sum_order', 'Сумма', array('class' => 'control-label')); !!}</p>
                                        {!! Form::text('sum_order', $fin_report->sum_order, array('name' => 'sum_order')) !!}
                                    </div>
                                    <div class="form-fr form-fr-half">
                                        <p>{!! Form::label('framework_count', 'Количество конструкций', array('class' => 'control-label')); !!}</p>
                                        {!! Form::text('framework_count', $fin_report->framework_count, array('name' => 'framework_count')) !!}
                                    </div>
                                    <div class="form-fr form-fr-half">
                                        <p>{!! Form::label('discount', 'Скидка (%)', array('class' => 'control-label')); !!}</p>
                                        {!! Form::text('discount', $fin_report->discount, array('name' => 'discount')) !!}
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group has-feedback">
                                <div class="form-group">
                                    <div class="form-fr">
                                        <span class="form-fr__label">{!! Form::label('', 'Данные клиента', array('class' => 'control-label')); !!}</span>
                                    </div>
                                    <div class="form-fr">
                                        <p>{!! Form::label('name', 'Имя', array('class' => 'control-label')); !!}</p>
                                        {!! Form::text('name', $fin_report->name, array('name' => 'name')) !!}
                                    </div>
                                    <div class="form-fr">
                                        <p>{!! Form::label('phone', 'Номер телефона', array('class' => 'control-label')); !!}</p>
                                        {!! Form::text('phone', $fin_report->phone, array('name' => 'phone')) !!}
                                    </div>
                                    <div class="form-fr">
                                        <p>{!! Form::label('email', 'Електронная почта', array('class' => 'control-label')); !!}</p>
                                        {!! Form::text('email', $fin_report->email, array('name' => 'email')) !!}
                                    </div>
                                    <div class="form-group">
                                        <div class="form-fr">
                                            <span class="form-fr__label">{!! Form::label('', '', array('class' => 'control-label')); !!}</span>
                                        </div>
                                        <div class="form-fr">
                                            <p>{!! Form::label('city', 'Город', array('class' => 'control-label')); !!}</p>
                                            {!! Form::text('city', $fin_report->city, array('name' => 'city')) !!}
                                        </div>
                                        <div class="form-fr">
                                            <p>{!! Form::label('street', 'Улица', array('class' => 'control-label')); !!}</p>
                                            {!! Form::text('street', $fin_report->street, array('name' => 'street')) !!}
                                        </div>
                                        <div class="form-fr form-fr-half">
                                            <p>{!! Form::label('house', 'Дом', array('class' => 'control-label')); !!}</p>
                                            {!! Form::text('house', $fin_report->house, array('name' => 'house')) !!}
                                        </div>
                                        <div class="form-fr form-fr-half">
                                            <p>{!! Form::label('flat', 'Квартира', array('class' => 'control-label')); !!}</p>
                                            {!! Form::text('flat', $fin_report->flat, array('name' => 'flat')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group has-feedback">
                                <div class="form-group">
                                    <div class="form-fr fr-checkbox">
                                        @if(isset(old('report')['0']['active']))
                                            <span class="form-fr__label">{{ Form::checkbox('report[0][active]', 1, true,['class' => 'field active', 'checked' => 'checked']) }}<span class="montag">Монтаж</span></span>
                                        @else
                                            <span class="form-fr__label">{{ Form::checkbox('report[0][active]', 0, false, ['class' => 'field active']) }}<span class="montag">Монтаж</span></span>
                                        @endif
                                    </div>
                                    <div class="active_inputs" style="display: inline-block">
                                        <div class="form-fr">
                                            <p>{!! Form::label('installer', 'Монтажник', array('class' => 'control-label')); !!}</p>
                                            {!! Form::text('installer', $fin_report->installer, array( $active ? '' : 'readonly', 'name' => 'installer')) !!}
                                        </div>
                                        <div class="form-fr">
                                            <p>{!! Form::label('area', 'Площадь', array('class' => 'control-label')); !!}</p>
                                            {!! Form::text('area', $fin_report->area, array( $active ? '' : 'readonly', 'name' => 'area')) !!}
                                        </div>
                                        <div class="form-fr">
                                            <p>{!! Form::label('sum', 'Сумма, грн', array('class' => 'control-label')); !!}</p>
                                            {!! Form::text('sum', $fin_report->sum, array( $active ? '' : 'readonly', 'name' => 'sum')) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                {!! Form::button('Сохранить', array('class' => 'btn dr_save_btn  center-block','type' => 'submit', 'id' => 'fin-save')) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    </div>
@stop


@section('tmp_js')
    <script type="text/javascript">
        $(document).on('click', '.active', function () {
            if ($(this).is(':checked')) {
                $(this).parent().parent().parent().find('.active_inputs').find(':input').prop('readonly', false);
            }
            else {
                $(this).parent().parent().parent().find('.active_inputs').find(':input').prop('readonly', true);
            }
        });

        $(function () {
            $(".datepicker").datepicker({
                // showOn: "button",
                // buttonImage: "/img/calendar.gif",
                // buttonImageOnly: true,
                // buttonText: "Select date",
                dateFormat: "yy-mm-dd"
            });
        });
    </script>
@endsection