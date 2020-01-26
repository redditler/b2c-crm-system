@extends('adminlte::page')

@section('content_header')
    <h1 class="content_header__h1">Просмотр диалога №{{ $callID }}</h1>
@endsection

@section('content')
<div style="color:#fff;margin-bottom:45px;">Оператор, принявший участие в данном диалоге: {{ $operateurName }}</div>
<div class="container" id="mainContainer">
	<div class="row">
		<div class="col-12">
	@foreach($callLog as $thisCallLine)
		@if($thisCallLine->question_id>0)
			<div class="col-12 mb-3">
				<div class="panel">
					<div class="panel-heading" style="font-size:9pt;">
						<i>{{ $thisCallLine->created_at }}</i>
			@if(\Illuminate\Support\Facades\Auth::user()->role_id == 1)
						<span class="label label-info goto-management" style="cursor:pointer;margin-left:5px;" data-id="{{ $thisCallLine->question_id }}">Открыть в конструкторе</span>
			@endif
						<strong style="margin-left:10px;">{{ $questions[$thisCallLine->question_id]['question_title'] }}</strong>
						<button
							type="button"
			@if($thisCallLine->is_removed == 1)
							class="btn btn-danger"
			@else
							class="btn btn-light"
			@endif
							style="float:right;"
							disabled>Отмена</button>
					</div>
					<div class="panel-body">
						{!! $questions[$thisCallLine->question_id]['question_text'][0] !!}
			@if($questions[$thisCallLine->question_id]['instructions'] != null)
						<hr><i>{!! $questions[$thisCallLine->question_id]['instructions'] !!}</i>
			@if(isset($questions[$thisCallLine->question_id]['question_text'][1]))
				@foreach($questions[$thisCallLine->question_id]['question_text'] as $thisQPartIdx=>$thisQuestionPart)
					@if($thisQPartIdx>0)
						<hr>{!! $thisQuestionPart !!}
					@endif
				@endforeach
			@endif
			@endif
					</div>
					<div class="panel-footer">
			@foreach($questions[$thisCallLine->question_id]['variants'] as $thisVariant)
						<button
							type="button"
				@if($thisVariant['id'] == $thisCallLine->variant_id)
							class="btn btn-info" style="margin-right:5px;width:auto;"
				@else
							class="btn btn-default" style="margin-right:5px;"
				@endif
							disabled>{{ $thisVariant['title'] }}</button>
			@endforeach
			@if((strlen($thisCallLine->fail_description)>0) and ($thisCallLine->variant_id == 0))
						<button type="button" class="btn btn-sm btn-info" style="float:right;width:auto;" disabled>Ни один вариант не подходит</button>
			@else
						<button type="button" class="btn btn-sm btn-default" style="float:right;" disabled>Ни один вариант не подходит</button>
			@endif
					</div>
				</div>
			</div>
			@if(strlen($thisCallLine->fail_description)>0)
			<div class="alert alert-info" style="position:relative;">
				<i style="position:absolute;bottom:10px;right:10px;">{{ $thisCallLine->updated_at }}</i>
				<strong>Оператором было добавлено следующее уточнение:</strong><br>
				{{ $thisCallLine->fail_description }}
			</div>
			@endif
			@foreach($questions[$thisCallLine->question_id]['variants'] as $thisVariant)
				@if($thisVariant['id'] == $thisCallLine->variant_id)
					@if(($thisVariant['link'] == -1) or ($thisVariant['link'] == -2))
						@php
							$dialogueResultSet = true;
						@endphp
					@endif
					@if($thisVariant['link'] == -1)
						<div class="alert alert-danger" style="position:relative;">
							<i style="position:absolute;bottom:10px;right:10px;">{{ $thisCallLine->created_at }}</i>
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Произошло неуспешное завершение диалога
						</div>
					@elseif($thisVariant['link'] == -2)
						<div class="alert alert-success" style="position:relative;">
							<i style="position:absolute;bottom:10px;right:10px;">{{ $thisCallLine->created_at }}</i>
							<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Произошло успешное завершение диалога
						</div>
					@endif
				@endif
			@endforeach
		@else
			@if(!isset($dialogueResultSet))
			<div class="alert alert-warning" style="position:relative;">
				<i style="position:absolute;bottom:10px;right:10px;">{{ $thisCallLine->created_at }}</i>
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Произошло неожиданное завершение диалога: сценарий не предусматривает продолжения.
				@if(strlen($thisCallLine->fail_description)>0)
				<hr><strong>Оператором было добавлено следующее уточнение:</strong><br>
				{{ $thisCallLine->fail_description }}
				@endif
			</div>
			@endif
		@endif
	@endforeach
		</div>
	</div>
</div>

@endsection

@section('js')
	@if(\Illuminate\Support\Facades\Auth::user()->role_id == 1)
<script>
	$('.goto-management').click(function () {
		var managementDirected = '{{ route('callscriptsManagementDirected', 'stub') }}';
		var managementWindow = window.open(managementDirected.replace('stub', $(this).attr('data-id')), '_blank');
		if(managementWindow) {
		    managementWindow.focus();
		}else{
		    alert('Пожалуйста, разрешите всплывающие окна для данного ресурса.');
		}
	});
</script>
	@endif
@endsection