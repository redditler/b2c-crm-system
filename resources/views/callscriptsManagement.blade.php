@extends('adminlte::page')

@section('content_header')
    <h1 class="content_header__h1">Управление сценариями разговора с клиентом</h1>
@endsection

@section('content')
<div class="container" id="mainContainer">
	<div class="row">
		<div class="col-12">
			<form id="questionSearch">
				<input type="number" class="form-control" style="width:115px;float:right;" id="formSearchField" placeholder="Поиск по ID">
			</form>
			<h5><button class="btn btn-sm" data-toggle="modal" data-target="#addTopicModal"><span class="glyphicon glyphicon-plus"></span></button> Основные темы скриптов</h5><br>
	@foreach($questionsList as $thisTopic)
		@php
			$csTopicsAssoc[$thisTopic['id']] = [
				'title'			=> $thisTopic['topic_name'],
				'description'	=> $thisTopic['topic_description'],
				'is_publicated'	=> $thisTopic['is_publicated']
			];
		@endphp
			<div class="card mb-3">
				<div class="card-header">
					<button class="btn btn-sm topic-edit" data-id="{{ $thisTopic['id'] }}"><span class="glyphicon glyphicon-pencil"></span></button> 
		@if($thisTopic['is_publicated'] == 1)
					<span class="glyphicon glyphicon-eye-open"></span> 
		@else
					<span class="glyphicon glyphicon-eye-close"></span> 
		@endif
					{{ $thisTopic['topic_name'] }}
				</div>
				<div class="card-body">
					<div id="visualNetwork{{ $thisTopic['id'] }}" class="visualNetwork"></div>
					<script type="text/javascript">
						document.addEventListener("DOMContentLoaded", function(event) { 
							var nodes = new vis.DataSet([
		@foreach($thisTopic['questions'] as $thisQuestion)
								{
									id: '{{ $thisQuestion['topic'] }}_{{ $thisQuestion['id'] }}', 
									label: '{{ htmlspecialchars($thisQuestion['question_title']) }}', 
									color : {
										border: '#FFFFFF',
										background: '#EDEDED',
										highlight: 
										{
											border: '#7EDBFF',
											background: '#ADE8FF'
										}
									},
									font :
									{
										color: '#0A456D'
									}
								},
		@endforeach
							]);
							var edges = new vis.DataSet([
		@foreach($thisTopic['questions'] as $thisQuestion)
			@php 
				$variantsExplained = json_decode($thisQuestion['variants'], 1);
			@endphp
			@if($thisQuestion['parent_id'] != -1)
								{from: '{{ $thisQuestion['topic'] }}_{{ $thisQuestion['id'] }}', to: '{{ $thisQuestion['topic'] }}_{{ $thisQuestion['parent_id'] }}', arrows: 'from'},
				@if(count($variantsExplained)>0)
					@foreach($variantsExplained as $thisExplainedVar)
								{from: '{{ $thisQuestion['topic'] }}_{{ $thisExplainedVar['link'] }}', to: '{{ $thisQuestion['topic'] }}_{{ $thisQuestion['id'] }}', arrows: 'from'},
					@endforeach
				@endif
			@endif
		@endforeach
							]);
							var container = document.getElementById('visualNetwork{{ $thisTopic['id'] }}');
							var data = {
								nodes: nodes,
								edges: edges
							};
							var options = {
								layout: {
									hierarchical: {
										enabled: true,
										direction: 'DU',
										sortMethod: 'directed',
										nodeSpacing: 50,
										treeSpacing: 50,
										levelSeparation: 100,
										edgeMinimization: false,
									}
								},
								nodes: {
									shape: 'box'
								},
								physics: {
									enabled: true,
									hierarchicalRepulsion: {
										centralGravity: 0.0,
										springLength: 200,
										springConstant: 0.01,
										nodeDistance: 150,
										damping: 0.09
									}
								}
							};
							var network = new vis.Network(container, data, options);
							network.on('click', function(properties) {
								if(typeof properties.nodes[0] !== "undefined"){
									var clickedEdge = properties.nodes[0].split('_');
								    $('.action-buttons-' + clickedEdge[0]).fadeIn().attr('data-id', clickedEdge[1]);
								}
							});
						});
					</script>
				</div>
				<div class="card-footer">
					<input type="button" style="float:right;margin-left:5px;" class="btn btn-sm btn-secondary remove-question action-buttons action-buttons-{{ $thisTopic['id'] }}" data-id="" data-action="remove" value="Удалить">
					<input type="button" style="float:right;margin-left:5px;" class="btn btn-sm btn-secondary add-quick-question action-buttons action-buttons-{{ $thisTopic['id'] }}" data-id="" data-topic="{{ $thisQuestion['topic'] }}" data-action="add-quick" value="Добавить быстрый">
					<input type="button" style="float:right;margin-left:5px;" class="btn btn-sm btn-secondary add-question action-buttons action-buttons-{{ $thisTopic['id'] }}" data-id="" data-topic="{{ $thisQuestion['topic'] }}" data-action="add" value="Добавить">
					<input type="button" style="float:right;" class="btn btn-sm btn-secondary edit-question action-buttons action-buttons-{{ $thisTopic['id'] }}" data-id="" data-action="edit" value="Правка">
				</div>
			</div>
	@endforeach
		</div>
	</div>
</div>
<div id="editQuestionModal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog callscripts-modal" role="document">
		<form id="editQuestionForm">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Правка вопроса</h4>
					<button type="button" class="close cs-modal-close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body cs-inner-modal">
					<div class="row">
						<div class="col-sm-12 mb-3">
							<h4>Заголовок вопроса:</h4>
							<input type="text" id="editQuestionTitle" name="question_title" class="form-control">
						</div>
						<div class="col-sm-12 mb-3">
							<h4>Текст вопроса:</h4>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='background-color:yellow;'>" data-tag-end="</span>" data-source="edit">
								<span class="glyphicon glyphicon-font" style="background-color:yellow;color:black;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:yellow;'>" data-tag-end="</span>" data-source="edit">
								<span class="glyphicon glyphicon-font" style="color:yellow;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:blue;'>" data-tag-end="</span>" data-source="edit">
								<span class="glyphicon glyphicon-font" style="color:blue;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:green;'>" data-tag-end="</span>" data-source="edit">
								<span class="glyphicon glyphicon-font" style="color:green;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:red;'>" data-tag-end="</span>" data-source="edit">
								<span class="glyphicon glyphicon-font" style="color:red;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<u>" data-tag-end="</u>" data-source="edit">
								<span class="glyphicon glyphicon-text-color"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<i>" data-tag-end="</i>" data-source="edit">
								<span class="glyphicon glyphicon-italic"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<b>" data-tag-end="</b>" data-source="edit">
								<span class="glyphicon glyphicon-bold"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-breaker format-buttons" data-source="edit">
								<span class="glyphicon glyphicon-scissors"></span>
							</button>
							<textarea id="editQuestionText" name="question_text" class="form-control" style="height:120px;"></textarea>
						</div>
						<div class="col-sm-12 mb-3">
							<h4>Текст инструкции:</h4>
							<textarea id="editQuestionInstruction" name="instructions" class="form-control" style="height:85px;"></textarea>
						</div>
						<div class="col-sm-12">
							<h4>Варианты ответов:</h4>
						</div>
						<div class="col-sm-12 mb-1 row" id="editQuestionVariants" style="margin-left: auto;margin-right: auto;"></div>
						<div class="col-sm-12" style="padding-top:3px;">
							<input type="button" class="btn btn-sm btn-success w-100 add-variants" style="width: -webkit-fill-available;" value="Добавить">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary edit-question-save">Сохранить</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div id="addQuestionModal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog callscripts-modal" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Создание нового вопроса</h4>
				<button type="button" class="close cs-modal-close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body cs-inner-modal">
				<div class="row">
					<div class="col-sm-12 mb-3">
						<h4>Заголовок вопроса:</h4>
						<input type="text" id="addQuestionTitle" name="question_title" class="form-control">
					</div>
					<div class="col-sm-12 mb-3">
						<h4>Текст вопроса:</h4>
						<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='background-color:yellow;'>" data-tag-end="</span>" data-source="add">
							<span class="glyphicon glyphicon-font" style="background-color:yellow;color:black;"></span>
						</button>
						<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:yellow;'>" data-tag-end="</span>" data-source="add">
							<span class="glyphicon glyphicon-font" style="color:yellow;"></span>
						</button>
						<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:blue;'>" data-tag-end="</span>" data-source="add">
							<span class="glyphicon glyphicon-font" style="color:blue;"></span>
						</button>
						<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:green;'>" data-tag-end="</span>" data-source="add">
							<span class="glyphicon glyphicon-font" style="color:green;"></span>
						</button>
						<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:red;'>" data-tag-end="</span>" data-source="add">
							<span class="glyphicon glyphicon-font" style="color:red;"></span>
						</button>
						<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<u>" data-tag-end="</u>" data-source="add">
							<span class="glyphicon glyphicon-text-color"></span>
						</button>
						<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<i>" data-tag-end="</i>" data-source="add">
							<span class="glyphicon glyphicon-italic"></span>
						</button>
						<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<b>" data-tag-end="</b>" data-source="add">
							<span class="glyphicon glyphicon-bold"></span>
						</button>
						<button type="button" class="btn btn-sm btn-primary insert-breaker format-buttons" data-source="add">
							<span class="glyphicon glyphicon-scissors"></span>
						</button>
						<textarea id="addQuestionText" name="question_text" class="form-control" style="height:120px;"></textarea>
					</div>
					<div class="col-sm-12 mb-3">
						<h4>Текст инструкции:</h4>
						<textarea id="addQuestionInstruction" name="instructions" class="form-control" style="height:85px;"></textarea>
					</div>
					<div class="col-sm-12">
						<h4>Варианты ответов:</h4>
					</div>
					<div class="col-sm-12 mb-1 row" id="addQuestionVariants" style="margin-left: auto;margin-right: auto;"></div>
					<div class="col-sm-12" style="padding-top:3px;">
						<input type="button" class="btn btn-sm btn-success w-100 add-variants" style="width: -webkit-fill-available;" value="Добавить">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary add-question-save">Создать</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>
<div id="removeQuestionModal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog callscripts-modal" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Удаление вопроса</h4>
				<button type="button" class="close cs-modal-close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body cs-inner-modal">
				<p style="color:#fff;">Вы уверены, что желаете удалить данный вопрос из сценария?</p>
				<p style="color:#fff;">Как бы грустно это ни звучало, но вернуть его будет невозможно, а кроме того он пропадет из тех диалогов, в истории которых оставил свой след.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary remove-agreement" data-id="">Да, удалить</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Нет, отменить</button>
			</div>
		</div>
	</div>
</div>
<div id="addTopicModal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog callscripts-modal" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Создание нового скрипта</h4>
				<button type="button" class="close cs-modal-close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body cs-inner-modal">
				<div class="row">
					<div class="col-sm-12 mb-3">
						<h4>Заголовок темы:</h4>
						<textarea id="addTopicTitle" name="topic_title" class="form-control"></textarea>
					</div>
					<div class="col-sm-12 mb-3">
						<h4>Описание темы:</h4>
						<textarea id="addTopicDescription" name="topic_description" class="form-control"></textarea>
					</div>
					<div class="col-sm-12 mb-3">
						<input type="checkbox" id="addTopicPublicated"> <label for="addTopicPublicated">Тема опубликована</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary save-created-topic">Создать</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
			</div>
		</div>
	</div>
</div>
<div id="editTopicModal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog callscripts-modal" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Правка скрипта</h4>
				<button type="button" class="close cs-modal-close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body cs-inner-modal">
				<div class="row">
					<div class="col-sm-12 mb-3">
						Заголовок темы:
						<textarea id="editTopicTitle" name="topic_title" class="form-control"></textarea>
					</div>
					<div class="col-sm-12 mb-3">
						Описание темы:
						<textarea id="editTopicDescription" name="topic_description" class="form-control"></textarea>
					</div>
					<div class="col-sm-12 mb-3">
						<input type="checkbox" id="editTopicPublicated"> <label for="editTopicPublicated">Тема опубликована</label>
					</div>
				</div>			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary save-edited-topic">Сохранить</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('css')
<link href="https://unpkg.com/vis-network@latest/dist/vis-network.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .visualNetwork {
        width: 100%;
        height: 50vh;
    }
    .modal-dialog.callscripts-modal {
    	margin-top: 0;
    	margin-bottom: 0;
    	height: 100vh;
    	display: flex;
    	flex-direction: column;
    	justify-content: center;
    }
    button.close.cs-modal-close {
    	position: absolute;
    	top: 15px;
    	right: 25px;
    }
    .modal-body.cs-inner-modal {
    	margin-top: -15px;
    	color: #fff;
    	font-family: Montserrat;
    }
    .modal-body.cs-inner-modal input[type=text], .modal-body.cs-inner-modal select, .modal-body.cs-inner-modal textarea {
    	font-family: Montserrat;
	    font-size: 12px;
	    font-weight: 400;
	    margin: auto;
	    padding-left: 10px;
	    color: #fff;
	    border: 1px solid #fff;
	    border-radius: 0;
	    background: transparent;
    }
    .cs-variant-sections {
    	margin-top: 3px;
    	padding-left: 1.5px;
    	padding-right: 1.5px;
    }
    	.cs-variant-sections.cs-first {
    		padding-right: 1.5px;
    	}
    	.cs-variant-sections.cs-last {
    		padding-left: 1.5px;
    	}
    .remove-variant {
    	height: 34px;
	    width: 46px;
    }
    .format-buttons {
    	padding-right: 15px;
	    padding-left: 15px;
	    float: right;
	    margin-left:5px;
	    margin-bottom:3px;
    }
    option {
    	color: #000;
    }
</style>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://unpkg.com/vis-network@latest/dist/vis-network.min.js"></script>
<script>
	$.ajaxSetup(
		{
			headers: {
				'X-CSRF-TOKEN': CONFIG_JS.csrfToken
			}
		}
	);
	function checkJSON(str) {
	    try {
	        JSON.parse(str);
	    } catch (e) {
	        return false;
	    }
	    return true;
	}
	var csTopicsData = jQuery.parseJSON('{!! json_encode($csTopicsAssoc) !!}');
	var linkedQuestions = {};
	$('.action-buttons').on('click', function() {
		if(($(this).attr('data-id') > 0) || ($(this).attr('data-action') == "add-quick")){
			var actionTypePassed = $(this).attr('data-action');
			$('#mainContainer').attr('data-last-action', $(this).attr('data-action'));
			$.post(
				'{{ route('callscriptsGetQuestionDetails') }}', 
				{ 
					question_id: $(this).attr('data-id')
				}, 
				function(response) {
					$('#editQuestionText').val(response.question_text);
					$('#editQuestionTitle').val(response.question_title);
					$('#editQuestionInstruction').val(response.instructions);
					if(actionTypePassed == "add-quick"){
						var variants = [];
					}else{
						var variants = jQuery.parseJSON(response.variants);
					}
					$('#editQuestionVariants').empty();
					$.each(variants, function( index, value ) {
						var appendedEl = `
							<div class="col-sm-5 cs-variant-sections cs-first variant-${value.id}">
								<input type="hidden" class="editedVariantId" value="${value.id}">
								<input type="text" class="editedVariantTitle form-control" value="${value.title}">
							</div>
							<div class="col-sm-2 cs-variant-sections variant-${value.id}">
								<select class="form-control editedVariantType" id="variant-type-${value.id}">
									<option value="1">Неприменимо</option>
									<option value="2">Позитивный</option>
									<option value="3">Нейтральный</option>
									<option value="4">Негативный</option>
								</select>
							</div>
							<div class="col-sm-4 cs-variant-sections variant-${value.id}">
								<select class="form-control editedVariantValue" id="variant-link-${value.id}">
									<option value="0">Назначить позже</option>
									<option value="-1">Тупиковая ветка</option>
									<option value="-2">Успешное завершение</option>`;
						linkedQuestions = response.linked;
						$.each(response.linked, function( linkedIndex, linkedValue ) {
							appendedEl = appendedEl + `
									<option value="${linkedIndex}">${linkedValue}</option>`;
						});
						appendedEl = appendedEl + `
								</select>
							</div>
							<div class="col-sm-1 cs-variant-sections cs-last variant-${value.id}">
								<button type="button" class="btn btn-sm btn-danger remove-variant" data-variant-id="${value.id}"><span class="glyphicon glyphicon-remove"></span></button>
							</div>`;
						$('#editQuestionVariants').append(appendedEl);
						$('#variant-link-' + value.id).val(value.link);
						$('#variant-type-' + value.id).val(value.type);
					});
				}
			);
			if($(this).attr('data-action') == "edit"){
				$('.edit-question-save').attr('data-id', $(this).attr('data-id'));
				$('#editQuestionModal').modal();
			}else if($(this).attr('data-action') == "add"){
				/*
					Здесь нужно получить список связанных вопросов перед тем, как что-то выводить.
				*/
				$('.add-question-save').attr('data-parent', $(this).attr('data-id'));
				$('.add-question-save').attr('data-topic', $(this).attr('data-topic'));
				$('#addQuestionModal').modal();
			}else if($(this).attr('data-action') == "add-quick"){
				$('.add-question-save').attr('data-parent', '-2');
				$('.add-question-save').attr('data-type', '3');
				$('.add-question-save').attr('data-topic', $(this).attr('data-topic'));
				$('#addQuestionModal').modal();
			}else if($(this).attr('data-action') == "remove"){
				$('.remove-agreement').attr('data-id', $(this).attr('data-id'));
				$('#removeQuestionModal').modal();
			}
		}else{
			alert('Сначала выберите вопрос');
		}
	});
	$('.insert-tag').click(function(){

	});
	$(document.body).on('click', '.remove-variant', function(e) {
		$('.variant-' + $(this).attr('data-variant-id')).remove();
	});
	$('.remove-agreement').click(function(){
		$.post(
			'{{ route('callscriptsUpdateQuestion') }}', 
			{
				id: 	$(this).attr('data-id'), 
				remove: true
			}, 
			function(response) {
				if(response.success == true){
					$('#removeQuestionModal').modal('hide');
				}
			}
		);
	});
	$('.topic-edit').click(function(){
		$('#editTopicTitle').val(csTopicsData[$(this).attr('data-id')].title);
		$('#editTopicDescription').val(csTopicsData[$(this).attr('data-id')].description);
		if(csTopicsData[$(this).attr('data-id')].is_publicated == 1){
			$('#editTopicPublicated').prop('checked', true);
		}else{
			$('#editTopicPublicated').prop('checked', false);
		}
		$('.save-edited-topic').attr('data-id', $(this).attr('data-id'));
		$('#editTopicModal').modal();
	});
	function sumbitTopics(data){
		$.post(
			'{{ route('callscriptsTopicsManager') }}', 
			data, 
			function(response) {
				if(response.success == true){
					csTopicsData[data.topic_id] = {
						title: 			data.topic_title, 
						description: 	data.topic_description, 
						is_publicated: 	data.is_publicated 
					}
					$('#editTopicModal').modal('hide');
					$('#addTopicModal').modal('hide');
				}
			}
		);
	}
	$('.save-edited-topic').click(function(){
		var dataToSubmit = {
			type: 					'update',
			topic_id: 				$(this).attr('data-id'), 
			topic_title: 			$('#editTopicTitle').val(), 
			topic_description: 		$('#editTopicDescription').val(), 
			is_publicated: 			($('#editTopicPublicated').prop('checked') ? 1 : 0)
		};
		sumbitTopics(dataToSubmit);
	});
	$('.save-created-topic').click(function(){
		var dataToSubmit = {
			type: 					'create',
			topic_title: 			$('#addTopicTitle').val(), 
			topic_description: 		$('#addTopicDescription').val(), 
			is_publicated: 			($('#addTopicPublicated').prop('checked') ? 1 : 0)
		};
		sumbitTopics(dataToSubmit);
	});
	$('.insert-breaker').click(function(){
		var qtextEl = $('#' + $(this).attr('data-source') + 'QuestionText');
        var currentPosition = $(qtextEl).get(0).selectionStart;
        var currentValue = $(qtextEl).val();
        $(qtextEl).val(currentValue.substring(0, currentPosition) + '{%break%}' + currentValue.substring(currentPosition));
	});
	$('.insert-tag').click(function(){
		var qtextEl = $('#' + $(this).attr('data-source') + 'QuestionText');
        if($(qtextEl).get(0).selectionStart != $(qtextEl).get(0).selectionEnd){
        	var currentValue = $(qtextEl).val();
        	$(qtextEl).val(currentValue.substring(0, $(qtextEl).get(0).selectionStart) + 
        		$(this).attr('data-tag-begin') + currentValue.substring($(qtextEl).get(0).selectionStart, $(qtextEl).get(0).selectionEnd) + 
        		$(this).attr('data-tag-end') + currentValue.substring($(qtextEl).get(0).selectionEnd));
        }
	});
	$(document.body).on('click', '.add-variants', function(e) {
		/*
			Здесь должно быть получение уникального ID
		*/
		var fieldsPref = '';
		if($('#mainContainer').attr('data-last-action') == "add" || $('#mainContainer').attr('data-last-action') == "add-quick"){
			fieldsPref = 'created';
		}else if($('#mainContainer').attr('data-last-action') == "edit"){
			fieldsPref = 'edited';
		}
		var thisUniqueID = Date.now();
		var appendedEl = `
						<div class="col-sm-5 cs-variant-sections cs-first variant-${thisUniqueID}">
							<input type="hidden" class="${fieldsPref}VariantId" value="${thisUniqueID}">
							<input type="text" class="form-control ${fieldsPref}VariantTitle">
						</div>
						<div class="col-sm-2 cs-variant-sections variant-${thisUniqueID}">
							<select class="form-control ${fieldsPref}VariantType">
								<option value="1">Неприменимо</option>
								<option value="2">Позитивный</option>
								<option value="3">Нейтральный</option>
								<option value="4">Негативный</option>
							</select>
						</div>
						<div class="col-sm-4 cs-variant-sections variant-${thisUniqueID}">
							<select class="form-control ${fieldsPref}VariantValue">
								<option value="0">Назначить позже</option>
								<option value="-1">Тупиковая ветка</option>
								<option value="-2">Успешное завершение</option>`;
		$.each(linkedQuestions, function ( linkedIndex, linkedValue ) {
			appendedEl = appendedEl + `
								<option value="${linkedIndex}">${linkedValue}</option>`;
		});
		appendedEl = appendedEl + `
							</select>
						</div>
						<div class="col-sm-1 cs-variant-sections cs-last variant-${thisUniqueID}">
							<button type="button" class="btn btn-sm btn-danger remove-variant" data-variant-id="${thisUniqueID}"><span class="glyphicon glyphicon-remove"></span></button>
						</div>`;
		if($('#mainContainer').attr('data-last-action') == "edit"){
			$('#editQuestionVariants').append(appendedEl);
		}else{
			$('#addQuestionVariants').append(appendedEl);
		}
	});
	$(document.body).on('click', '.edit-question-save', function(e) {
		e.preventDefault();
		var editedVariantIds = [];
		var editedVariantTitles = [];
		var editedVariantValues = [];
		var editedVariantTypes = [];
		$.each($('.editedVariantId'), function ( variantIdIndex, variantIdValue ) {
			editedVariantIds.push($(variantIdValue).val());
		});
		$.each($('.editedVariantTitle'), function ( variantTitleIndex, variantTitleValue ) {
			editedVariantTitles.push($(variantTitleValue).val());
		});
		$.each($('.editedVariantValue'), function ( variantVarIndex, variantVarValue ) {
			editedVariantValues.push($(variantVarValue).val());
		});
		$.each($('.editedVariantType'), function ( variantTypeIndex, variantTypeValue ) {
			editedVariantTypes.push($(variantTypeValue).val());
		});
		var editedDataObj = {
			id:				$(this).attr('data-id'),
			question_title: $('#editQuestionTitle').val(), 
			question_text: 	$('#editQuestionText').val(),
			instructions: 	$('#editQuestionInstruction').val(), 
			variant_ids: 	editedVariantIds,
			variant_titles: editedVariantTitles,
			variant_links:  editedVariantValues,
			variant_types: 	editedVariantTypes 
		};
		$.post(
			'{{ route('callscriptsUpdateQuestion') }}', 
			editedDataObj, 
			function(response) {
				$('#editQuestionModal').modal('hide');
			}
		);
	});
	$(document.body).on('click', '.add-question-save', function(e) {
		e.preventDefault();
		var createdVariantIds = [];
		var createdVariantTitles = [];
		var createdVariantValues = [];
		var createdVariantTypes = [];
		$.each($('.createdVariantId'), function ( variantIdIndex, variantIdValue ) {
			createdVariantIds.push($(variantIdValue).val());
		});
		$.each($('.createdVariantTitle'), function ( variantTitleIndex, variantTitleValue ) {
			createdVariantTitles.push($(variantTitleValue).val());
		});
		$.each($('.createdVariantValue'), function ( variantVarIndex, variantVarValue ) {
			createdVariantValues.push($(variantVarValue).val());
		});
		$.each($('.createdVariantType'), function ( variantTypeIndex, variantTypeValue ) {
			createdVariantTypes.push($(variantTypeValue).val());
		});
		var createdDataObj = {
			question_title: $('#addQuestionTitle').val(),
			question_text: 	$('#addQuestionText').val(),
			instructions: 	$('#addQuestionInstruction').val(), 
			parent_id:      parseInt($(this).attr('data-parent')),
			type:  			(typeof $(this).attr('data-type') === "undefined" ? 2 : $(this).attr('data-type')),
			topic:  		parseInt($(this).attr('data-topic')), 
			variant_ids: 	createdVariantIds,
			variant_titles: createdVariantTitles,
			variant_links:  createdVariantValues,
			variant_types: 	createdVariantTypes
		};
		$.post(
			'{{ route('callscriptsCreateQuestion') }}', 
			createdDataObj, 
			function(response) {
				console.log(response);
				$('#addQuestionModal').modal('hide');
			}
		);
	});
	$('#questionSearch').submit(function(event){
		event.preventDefault();
		var questionToFind = $('#formSearchField').val();
		if(questionToFind>0){
			var managementDirected = '{{ route('callscriptsManagementDirected', 'stub') }}';
			window.location.href = managementDirected.replace('stub', questionToFind);
		}
	});
	/*
		Forced query to direct question
	*/
	@if($directedQuestion)
	$.post(
		'{{ route('callscriptsGetQuestionDetails') }}', 
		{ 
			question_id: '{{ $directedQuestion }}'
		}, 
		function(response) {
			$('#mainContainer').attr('data-last-action', 'edit');
			$('.edit-question-save').attr('data-id', '{{ $directedQuestion }}');
			$('#editQuestionText').val(response.question_text);
			$('#editQuestionTitle').val(response.question_title);
			$('#editQuestionInstruction').val(response.instructions);
			var variants = jQuery.parseJSON(response.variants);
			$('#editQuestionVariants').empty();
			$.each(variants, function( index, value ) {
				var appendedEl = `
					<div class="col-sm-5 cs-variant-sections cs-first variant-${value.id}">
						<input type="hidden" class="editedVariantId" value="${value.id}">
						<input type="text" class="editedVariantTitle form-control" value="${value.title}">
					</div>
					<div class="col-sm-2 cs-variant-sections variant-${value.id}">
						<select class="form-control editedVariantType" id="variant-type-${value.id}">
							<option value="1">Неприменимо</option>
							<option value="2">Позитивный</option>
							<option value="3">Нейтральный</option>
							<option value="4">Негативный</option>
						</select>
					</div>
					<div class="col-sm-4 cs-variant-sections variant-${value.id}">
						<select class="form-control editedVariantValue" id="variant-link-${value.id}">
							<option value="0">Назначить позже</option>
							<option value="-1">Тупиковая ветка</option>
							<option value="-2">Успешное завершение</option>`;
				linkedQuestions = response.linked;
				$.each(response.linked, function( linkedIndex, linkedValue ) {
					appendedEl = appendedEl + `
							<option value="${linkedIndex}">${linkedValue}</option>`;
				});
				appendedEl = appendedEl + `
						</select>
					</div>
					<div class="col-sm-1 cs-variant-sections cs-last variant-${value.id}">
						<button type="button" class="btn btn-sm btn-danger remove-variant" data-variant-id="${value.id}"><span class="glyphicon glyphicon-remove"></span></button>
					</div>`;
				$('#editQuestionVariants').append(appendedEl);
				$('#variant-link-' + value.id).val(value.link);
				$('#variant-type-' + value.id).val(value.type);
			});
			$('#editQuestionModal').modal();
		}
	);
	@endif
</script>
@endsection