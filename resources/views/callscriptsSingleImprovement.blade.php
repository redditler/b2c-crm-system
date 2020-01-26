@extends('adminlte::page')

@section('content_header')
    <h1 class="content_header__h1">Улучшение диалога</h1>
@endsection

@section('content')
<div class="container" id="mainContainer">
	<div class="row">
		<div class="col-12">
			<div class="panel">
				<div class="panel-footer">
					<strong>Рассмотрение исправления, предложенного пользователем</strong>
				</div>
				<div class="panel-body">
					<div class="row">

						<div class="col-sm-4">Предложение было подано:</div>
						<div class="col-sm-8">{{ $improvement->created_at_string }}</div>					

						<div class="col-sm-4">Автор:</div>
						<div class="col-sm-8">{{ $improvement->operateur_name }}</div>	

						<div class="col-sm-4">Скрипт, содержащий вопрос:</div>
						<div class="col-sm-8">{{ $improvement->topic_data->topic_name }}</div>	

						<div class="col-sm-4">Рассматриваемый вопрос:</div>
						<div class="col-sm-8">{{ $improvement->original_question->question_title }}</div>	

						<div class="col-sm-12" style="margin-top:25px;">
							<strong>Исходный текст вопроса (актуальный на данный момент):</strong>
						</div>
						<div class="col-sm-12" style="margin-top:15px;">
							<div class="alert alert-info" role="alert">
	@foreach($improvement->original_question->question_text as $partID=>$questionPart)
		@if($partID>0)
								<hr>
		@endif
								{{ $questionPart }}
	@endforeach
							</div>
						</div>

						<div class="col-sm-12" style="margin-top:25px;">
							<strong>Предложенный пользователем вариант:</strong>
						</div>
						<div class="col-sm-12" style="margin-top:15px;">
							<div class="alert alert-success" role="alert">
								{{ $improvement->improved_text }}
							</div>
						</div>

					</div>
				</div>
				<div class="panel-footer" align="right">
					<span class="label label-info goto-management" style="cursor:pointer;margin-left:5px;" data-id="{{ $improvement->question_id }}">Открыть в конструкторе</span>
				</div>
			</div>
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