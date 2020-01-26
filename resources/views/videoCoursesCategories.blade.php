@extends('adminlte::page')

@section('content_header')
    <h1 class="content_header__h1">Разделы видео</h1>
@endsection

@section('content')
	@if(Auth::user()->role_id == 1)
	<button class="btn btn-success" id="addCategory">Добавить раздел</button>
	@endif
	<div class="row" style="margin-top:25px;">
	@foreach($categories as $thisCategory)
		<div class="col-md-4">
			<div class="panel">
				<div class="panel-head" style="padding-left: 20px;">
					<h4>{{ $thisCategory->category_title }}</h4>
				</div>
				<div class="panel-body">
					Всего видео в разделе: <strong>{{ $thisCategory->videos_count }}</strong><br>
					Всего просмотров видео в разделе: <strong>{{ $thisCategory->video_views }}</strong>
				</div>
				<div class="panel-footer">
					<button class="btn btn-sm btn-default videocat-edit"
						data-id="{{ $thisCategory->id }}"
						data-title="{{ $thisCategory->category_title }}">
						<i class="fa fa-pencil"></i>
					</button>
				</div>
			</div>
		</div>
	@endforeach
	</div>
	<div id="mkNewCategory" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form action="{{ route('videocourses.categoriesManage') }}" method="POST">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Создание нового раздела</h4>
						<button type="button" class="close vc-modal-close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body vcourses-modal">
					    {{ csrf_field() }}
					    <input type="hidden" name="type" value="add">
						<div class="row">
							<div id="uploadErreursMain" class="alert alert-danger" style="margin-left: 15px;margin-right: 15px;display:none;">
								При создании новой категории произошли некоторые ошибки:
								<ul id="uploadErreurs"></ul>
							</div>
							<div class="col-sm-12 mb-3">
								<h4>Заголовок раздела:</h4>
								<input type="text" id="addVideoTitle" name="title" class="form-control">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="sumbit" class="btn btn-primary" data-id="">Создать</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div id="updateCategory" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form action="{{ route('videocourses.categoriesManage') }}" method="POST">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Правка данных раздела</h4>
						<button type="button" class="close vc-modal-close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body vcourses-modal">
					    {{ csrf_field() }}
					    <input type="hidden" name="occasion_id" id="innerUpdateID" value="">
					    <input type="hidden" name="type" value="edit">
						<div class="row">
							<div id="updateErreursMain" class="alert alert-danger" style="margin-left: 15px;margin-right: 15px;display:none;">
								При редактировании раздела произошли следующие ошибки:
								<ul id="updateErreurs"></ul>
							</div>
							<div class="col-sm-12 mb-3">
								<h4>Заголовок раздела:</h4>
								<input type="text" id="updateVideoTitle" name="title" class="form-control">
							</div>
							<div class="col-sm-12 mb-3" style="margin-top:15px;">
								<h4>Удаление раздела:</h4>
								<input type="checkbox" id="removeCategoryChb" name="remove" value="true"> <label for="removeCategoryChb">Удалить данный раздел</label><br>
								<span style="font-size:8pt;">Пожалуйста, учтите: категория будет удалена сразу же после отправления формы, предупреждений больше не будет.</span>
							</div>
							<div class="col-sm-12 mb-3">
								<h4>Переместить видео:</h4>
								<select name="replacement_category" class="form-control">
									<option value="false">Никуда, удалить все видео</option>
	@foreach($categories as $thisCategory)
									<option value="{{ $thisCategory->id }}">{{ $thisCategory->category_title }}</option>
	@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button id="updateCatBtn" type="sumbit" class="btn btn-primary" data-id="">Сохранить</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection

@section('css')
<style type="text/css">
	.modal-body.vcourses-modal input[type=text], .modal-body.vcourses-modal select, .modal-body.vcourses-modal textarea {
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
	.modal-dialog.vcourses-modal {
		margin-top: 0;
		margin-bottom: 0;
		height: 100vh;
		display: flex;
		flex-direction: column;
		justify-content: center;
	}
	button.close.vc-modal-close {
		position: absolute;
		top: 15px;
		right: 25px;
	}
	.modal-body.vcourses-modal {
		margin-top: -15px;
		color: #fff;
		font-family: Montserrat;
	}
	option {
		color: #000;
	}
	.vc-more-text {
		background: -moz-linear-gradient(top,  rgba(255,255,255,0) 0%, rgba(255,255,255,0.66) 52%, rgba(255,255,255,1) 79%);
		background: -webkit-linear-gradient(top,  rgba(255,255,255,0) 0%,rgba(255,255,255,0.66) 52%,rgba(255,255,255,1) 79%);
		background: linear-gradient(to bottom,  rgba(255,255,255,0) 0%,rgba(255,255,255,0.66) 52%,rgba(255,255,255,1) 79%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00ffffff', endColorstr='#ffffff',GradientType=0 );
		position: absolute;
		bottom: 0;
		width: 100%;
		height: 35px;
		text-align: center;
		padding-top: 10px;
		cursor: pointer;
		text-shadow: -1px 0px 20px #FFFFFF;
	}
	.video-description {
		text-align: justify;
		height:120px;
		overflow:hidden;
		position:relative;
		padding-top:10px;
		padding-bottom:35px;
	}
</style>
@endsection

@section('js')
<script type="text/javascript">
	$.ajaxSetup({headers: {'X-CSRF-TOKEN': CONFIG_JS.csrfToken}});
	$('#addCategory').click(function(){
		$('#mkNewCategory').modal('show');
	});
	$('.videocat-edit').click(function(){
		$('select[name=replace_category] > option').prop('disabled', false);
		$('select[name=replace_category] > option[value=' + $(this).attr('data-id') + ']').prop('disabled', true);
		$('#updateVideoTitle').val($(this).attr('data-title'));
		$('#removeCategoryChb').prop('checked', false);
		$('#innerUpdateID').val($(this).attr('data-id'));
		$('#updateCategory').modal('show');
	});
</script>
@endsection
