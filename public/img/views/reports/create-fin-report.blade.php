@extends('adminlte::page')

@section('title', 'Steko')
@section('content')
    <div class="container">
        <h1 class="fin-rep-header">Финансовый отчет</h1>
                <div class="panel panel-default" id="form-fr-panel">
                    <div class="panel-body">
                        @include('partials.form-status')

                        {!! Form::open(array('action' => 'FinPlanController@store', 'method' => 'POST', 'role' => 'form')) !!}

                        {!! csrf_field() !!}

                        <div class="box-body">
                            <div id="reports">
                                <div class="report">
                                    {!! Form::hidden('', Auth::user()->id, array('name'=>'report[0][user_id]')) !!}
                                    {!! Form::hidden('', Auth::user()->branch_id, array('name'=>'report[0][branch_id]')) !!}
                                    <div class="form-group has-feedback">
                                            <div class="form-group date_picker_input dr_header_date" id="fr-date">
                                                {!! Form::text('', old('report')['0']['date'], array('class' => 'datepicker', 'name'=>'report[0][date]')) !!}
                                            </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <div class="form-group" >
                                            <div class="form-fr">
                                                <span class="form-fr__label">{!! Form::label('', 'Заказ', array('class' => 'control-label')); !!}</span>
                                            </div>
                                            <div class="form-fr">
                                                <p>{!! Form::label('num_order', '№ заказа', array('class' => 'control-label')); !!}</p>
                                                {!! Form::text('num_order', old('report')['0']['num_order'], array('name' => 'report[0][num_order]')) !!}
                                            </div>
                                            <div class="form-fr">
                                                <p>{!! Form::label('sum_order', 'Сумма', array('class' => 'control-label')); !!}</p>
                                                {!! Form::text('sum_order', old('report')['0']['sum_order'], array('name' => 'report[0][sum_order]')) !!}
                                            </div>
                                            <div class="form-fr form-fr-half">
                                                <p>{!! Form::label('framework_count', 'Количество конструкций', array('class' => 'control-label')); !!}</p>
                                                {!! Form::text('framework_count', old('report')['0']['framework_count'], array('name' => 'report[0][framework_count]')) !!}
                                            </div>
                                            <div class="form-fr form-fr-half">
                                                <p>{!! Form::label('discount', 'Скидка (%)', array('class' => 'control-label')); !!}</p>
                                                {!! Form::text('discount', old('report')['0']['discount'], array('name' => 'report[0][discount]')) !!}
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
                                                    {!! Form::text('name', old('report')['0']['name'], array('name' => 'report[0][name]')) !!}
                                                </div>
                                                <div class="form-fr">
                                                    <p>{!! Form::label('phone', 'Номер телефона', array('class' => 'control-label')); !!}</p>
                                                    {!! Form::text('phone', old('report')['0']['phone'], array('name' => 'report[0][phone]')) !!}
                                                </div>
                                                <div class="form-fr">
                                                    <p>{!! Form::label('email', 'Електронная почта', array('class' => 'control-label')); !!}</p>
                                                    {!! Form::text('email', old('report')['0']['email'], array('name' => 'report[0][email]')) !!}
                                                </div>
                                            <div class="form-group">
                                                <div class="form-fr">
                                                    <span class="form-fr__label">{!! Form::label('', '', array('class' => 'control-label')); !!}</span>
                                                </div>
                                                <div class="form-fr">
                                                    <p>{!! Form::label('city', 'Город', array('class' => 'control-label')); !!}</p>
                                                    {!! Form::text('city', old('report')['0']['city'], array('name' => 'report[0][city]')) !!}
                                                </div>
                                                <div class="form-fr">
                                                    <p>{!! Form::label('street', 'Улица', array('class' => 'control-label')); !!}</p>
                                                    {!! Form::text('street', old('report')['0']['street'], array('name' => 'report[0][street]')) !!}
                                                </div>
                                                <div class="form-fr form-fr-half">
                                                    <p>{!! Form::label('house', 'Дом', array('class' => 'control-label')); !!}</p>
                                                    {!! Form::text('house', old('report')['0']['house'], array('name' => 'report[0][house]')) !!}
                                                </div>
                                                <div class="form-fr form-fr-half">
                                                    <p>{!! Form::label('flat', 'Квартира', array('class' => 'control-label')); !!}</p>
                                                    {!! Form::text('flat', old('report')['0']['flat'], array('name' => 'report[0][flat]')) !!}
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
                                                    {!! Form::text('installer', old('report')['0']['installer'], array( isset(old('report')['0']['active']) ? '' : 'readonly', 'name' => 'report[0][installer]', 'value' => '0')) !!}
                                                </div>
                                                <div class="form-fr">
                                                    <p>{!! Form::label('area', 'Площадь', array('class' => 'control-label')); !!}</p>
                                                    {!! Form::text('area', old('report')['0']['area'], array(isset(old('report')['0']['active']) ? '' : 'readonly', 'name' => 'report[0][area]', 'value' => '0')) !!}
                                                </div>
                                                <div class="form-fr">
                                                    <p>{!! Form::label('sum', 'Сумма, грн', array('class' => 'control-label')); !!}</p>
                                                    {!! Form::text('sum', old('report')['0']['sum'], array(isset(old('report')['0']['active']) ? '' : 'readonly', 'name' => 'report[0][sum]', 'value' => '0')) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(count(old('report'))>1)
                                    @foreach(old('report') AS $key=>$val)
                                        @if($key != 0)
                                            <hr>
                                            <div class="report">
                                                {!! Form::hidden('', Auth::user()->id, array('name'=>'report[0][user_id]')) !!}
                                                {!! Form::hidden('', Auth::user()->branch_id, array('name'=>'report[0][branch_id]')) !!}
                                                <div class="form-group has-feedback">
                                                    <div class="form-group date_picker_input dr_header_date" id="fr-date">
                                                        {!! Form::text('', old('report')['0']['date'], array('class' => 'datepicker', 'name'=>'report[0][date]')) !!}
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback">
                                                    <div class="form-group" >
                                                        <div class="form-fr">
                                                            <span class="form-fr__label">{!! Form::label('', 'Заказ', array('class' => 'control-label')); !!}</span>
                                                        </div>
                                                        <div class="form-fr">
                                                            <p>{!! Form::label('num_order', '№ заказа', array('class' => 'control-label')); !!}</p>
                                                            {!! Form::text('num_order', old('report')['0']['num_order'], array('name' => 'report[0][num_order]')) !!}
                                                        </div>
                                                        <div class="form-fr">
                                                            <p>{!! Form::label('sum_order', 'Сумма', array('class' => 'control-label')); !!}</p>
                                                            {!! Form::text('sum_order', old('report')['0']['sum_order'], array('name' => 'report[0][sum_order]')) !!}
                                                        </div>
                                                        <div class="form-fr form-fr-half">
                                                            <p>{!! Form::label('framework_count', 'Количество конструкций', array('class' => 'control-label')); !!}</p>
                                                            {!! Form::text('framework_count', old('report')['0']['framework_count'], array('name' => 'report[0][framework_count]')) !!}
                                                        </div>
                                                        <div class="form-fr form-fr-half">
                                                            <p>{!! Form::label('discount', 'Скидка (%)', array('class' => 'control-label')); !!}</p>
                                                            {!! Form::text('discount', old('report')['0']['discount'], array('name' => 'report[0][discount]')) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group has-feedback">
                                                    <div class="form-group">
                                                        <div class="form-fr">
                                                            <span class="form-fr__label">{!! Form::label('', 'Данные клиента', array('class' => 'control-label')); !!}</span>
                                                        </div>
                                                        <div class="form-fr">
                                                            <p>{!! Form::label('name', 'Имя', array('class' => 'control-label')); !!}</p>
                                                            {!! Form::text('name', old('report')['0']['name'], array('name' => 'report[0][name]')) !!}
                                                        </div>
                                                        <div class="form-fr">
                                                            <p>{!! Form::label('phone', 'Номер телефона', array('class' => 'control-label')); !!}</p>
                                                            {!! Form::text('phone', old('report')['0']['phone'], array('name' => 'report[0][phone]')) !!}
                                                        </div>
                                                        <div class="form-fr">
                                                            <p>{!! Form::label('email', 'Електронная почта', array('class' => 'control-label')); !!}</p>
                                                            {!! Form::text('email', old('report')['0']['email'], array('name' => 'report[0][email]')) !!}
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-fr">
                                                                <span class="form-fr__label">{!! Form::label('', '', array('class' => 'control-label')); !!}</span>
                                                            </div>
                                                            <div class="form-fr">
                                                                <p>{!! Form::label('city', 'Город', array('class' => 'control-label')); !!}</p>
                                                                {!! Form::text('city', old('report')['0']['city'], array('name' => 'report[0][city]')) !!}
                                                            </div>
                                                            <div class="form-fr">
                                                                <p>{!! Form::label('street', 'Улица', array('class' => 'control-label')); !!}</p>
                                                                {!! Form::text('street', old('report')['0']['street'], array('name' => 'report[0][street]')) !!}
                                                            </div>
                                                            <div class="form-fr form-fr-half">
                                                                <p>{!! Form::label('house', 'Дом', array('class' => 'control-label')); !!}</p>
                                                                {!! Form::text('house', old('report')['0']['house'], array('name' => 'report[0][house]')) !!}
                                                            </div>
                                                            <div class="form-fr form-fr-half">
                                                                <p>{!! Form::label('flat', 'Квартира', array('class' => 'control-label')); !!}</p>
                                                                {!! Form::text('flat', old('report')['0']['flat'], array('name' => 'report[0][flat]')) !!}
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
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
                                                                {!! Form::text('installer', old('report')['0']['installer'], array( isset(old('report')['0']['active']) ? '' : 'readonly', 'name' => 'report[0][installer]', 'value' => '0')) !!}
                                                            </div>
                                                            <div class="form-fr">
                                                                <p>{!! Form::label('area', 'Площадь', array('class' => 'control-label')); !!}</p>
                                                                {!! Form::text('area', old('report')['0']['area'], array(isset(old('report')['0']['active']) ? '' : 'readonly', 'name' => 'report[0][area]', 'value' => '0')) !!}
                                                            </div>
                                                            <div class="form-fr">
                                                                <p>{!! Form::label('sum', 'Сумма, грн', array('class' => 'control-label')); !!}</p>
                                                                {!! Form::text('sum', old('report')['0']['sum'], array(isset(old('report')['0']['active']) ? '' : 'readonly', 'name' => 'report[0][sum]', 'value' => '0')) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <hr>
                                                <div class="form-group has-feedback row">
                                                    <div class="form-group">
                                                        <div class="col-md-2">
                                                            @if(array_key_exists('active',$val))
                                                                {{ Form::checkbox('report['.$key.'][active]', 1, true,['class' => 'field active', 'checked' => 'checked']) }}
                                                            @else
                                                                {{ Form::checkbox('report['.$key.'][active]', 0, null, ['class' => 'field active']) }}
                                                            @endif
                                                        </div>
                                                        <div class="active_inputs">
                                                            <div class="col-md-2 col-lg-offset-1">
                                                                {!! Form::label('installer', 'Монтажник', array('class' => 'control-label')); !!}
                                                                {!! Form::text('installer', $val['installer'], array(array_key_exists('active',$val) ? '' : 'readonly', 'name' => 'report['.$key.'][installer]', 'value' => '0')) !!}
                                                            </div>
                                                            <div class="col-md-2">
                                                                {!! Form::label('area', 'Площадь', array('class' => 'control-label')); !!}
                                                                {!! Form::text('area', $val['area'], array(array_key_exists('active',$val) ? '' : 'readonly', 'name' => 'report['.$key.'][area]', 'value' => '0')) !!}
                                                            </div>
                                                            <div class="col-md-2">
                                                                {!! Form::label('sum', 'Сумма, грн', array('class' => 'control-label')); !!}
                                                                {!! Form::text('sum', $val['sum'], array(array_key_exists('active',$val) ? '' : 'readonly', 'name' => 'report['.$key.'][sum]', 'value' => '0')) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            {!! Form::button('Сохранить', array('class' => 'btn dr_save_btn  center-block','type' => 'submit', 'id' => 'fin-save')) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
        <div id="add_more" class="btn btn-block fin-rep-btn-confirm">Добавить еще</div>
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

        $('#add_more').click(function () {
            var div = $('.report:first').clone();
            $('#reports').append('<hr>');
            var html = $('#reports').append(div);
            $('#reports').find('.report').last().find('.form-group').find(':input').val('');
            $('#reports').find('.report').last().find('.active_inputs').find(':input').prop('readonly', true);
            $('#reports').find('.report').last().find('.active').prop("checked", false);
            //prepare datepicker
            $('#reports').find('.report').last().find('.datepicker').removeAttr('id');
            $('#reports').find('.report').last().find('.datepicker').removeClass('hasDatepicker');
            $('#reports').find('.report').last().find('.ui-datepicker-trigger').remove();
            //end prepare datepicker
            $('#reports').find('.report').last().find('[name]').each(function () {
                this.name = this.name.replace(/\[(\d+)\]/, function (str, p1) {
                    return '[' + (parseInt(p1, 10) + 1) + ']'
                });
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