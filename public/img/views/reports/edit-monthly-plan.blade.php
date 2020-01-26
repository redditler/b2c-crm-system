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

                        {!! Form::open(array('action' => array('MonthlyPlanController@update',$monthly_plan->id), 'method' => 'PUT')) !!}

                        {!! csrf_field() !!}

                        <div class="box-body">
                            <div class="form-group has-feedback row">
                                {!! Form::label('year', 'Год', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::select('year', $years, $monthly_plan->year, array('id' => 'year', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                        <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                        aria-hidden="true"></i></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-feedback row">
                                {!! Form::label('month', 'Месяц', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::select('month', $months, $monthly_plan->month, array('id' => 'month', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                        <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                        aria-hidden="true"></i></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-feedback row">
                                {!! Form::label('branch_id', 'Филиал' , array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::select('branch_id', $branches, $monthly_plan->branch_id, array('id' => 'branch_id', 'class' => 'form-control', 'style' => 'padding-right: 14.5px;')) !!}
                                        <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                        aria-hidden="true"></i></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-feedback row">
                                {!! Form::label('frameworks', 'Конструкций', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::number('frameworks', $monthly_plan->frameworks, array('id' => 'frameworks', 'class' => 'form-control')) !!}
                                        <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                        aria-hidden="true"></i></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-feedback row">
                                {!! Form::label('sum', 'Сумма', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::number('sum', $monthly_plan->sum, array('id' => 'sum', 'class' => 'form-control')) !!}
                                        <label class="input-group-addon" for="email"><i class="fa fa-fw fa-users "
                                                                                        aria-hidden="true"></i></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group has-feedback row">
                                {!! Form::label('active', 'Активно', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {{ Form::checkbox('active',1, ($monthly_plan->active == 1 ? true : null), ['class' => 'field', 'name' => 'active']) }}
                                    </div>
                                </div>
                            </div>
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