@extends('adminlte::page')

@section('content_header')
    <h1 class="content_header__h1">Просмотры видео</h1>
@endsection

@section('content')
	<button class="btn btn-success" id="upload">Загрузить новое видео</button>
	<button class="btn btn-success" id="categories">Управление разделами</button>
	<div class="container" id="mainContainer">
		<div class="row">
			<div class="col-12">
	@if($viewedVideos->count()>0)
				<table class="table small dataTable">
					<thead>
						<tr>
							<td>Время начала просмотра</td>
							<td>Оператор</td>
							<td>Видео</td>
						</tr>
					</thead>
					<tbody>
		@foreach($viewedVideos as $thisVideo)
						<tr>
							<td style="vertical-align: middle;text-align:center;">{{ $thisVideo->viewed_at }}</td>
							<td style="vertical-align: middle;text-align:center;">{{ $thisVideo->operateur }}</td>
							<td style="vertical-align: middle;text-align:center;">{{ $thisVideo->title }}</td>
						</tr>
		@endforeach
					</tbody>
				</table>
	@endif
			{{ $viewedVideos->links() }}
			</div>
		</div>
	</div>
	<div id="uploadNewVideo" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form action="{{ route('videocourses.upload') }}" method="POST" enctype="multipart/form-data">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Загрузка видео на сервер</h4>
						<button type="button" class="close vc-modal-close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body vcourses-modal">
					    {{ csrf_field() }}
						<div class="row">
							<div id="uploadErreursMain" class="alert alert-danger" style="margin-left: 15px;margin-right: 15px;display:none;">
								При загрузке видео произошли некоторые ошибки:
								<ul id="uploadErreurs"></ul>
							</div>
							<div class="col-sm-12 mb-3">
								<h4>Заголовок видео:</h4>
								<input type="text" id="addVideoTitle" name="video_title" class="form-control">
							</div>
							<div class="col-sm-12 mb-3">
								<h4>Описание видео:</h4>
								<textarea id="addVideoDescription" name="video_description" class="form-control" style="height:120px;"></textarea>
							</div>
							<div class="col-sm-12 mb-3">
								<h4>Раздел для публикации:</h4>
								<select name="video_category" class="form-control">
		@foreach($categories as $thisCategory)
									<option value="{{ $thisCategory->id }}">{{ $thisCategory->category_title }}</option>
		@endforeach
								</select>
							</div>
							<div class="col-sm-12 mb-3">
								<h4>Область видимости (группы):</h4>
								<select style="width:100%;height:120px;" name="video_groups[]" multiple>
									<option value="false">Не применять</option>
		@if(isset($userGroups))
									<optgroup label="Группы">
			@foreach($userGroups as $thisGroup)
										<option value="group:{{ $thisGroup->id }}">{{ $thisGroup->name }}</option>
			@endforeach
									</optgroup>
		@endif
		@if(isset($userRoles))
									<optgroup label="Роли">
			@foreach($userRoles as $thisRole)
										<option value="role:{{ $thisRole->id }}">{{ $thisRole->name }}</option>
			@endforeach
									</optgroup>
		@endif
								</select>
							</div>
							<div class="col-sm-12 mb-3">
								<h4>Область видимости (служащие):</h4>
								<select style="width:100%;height:120px;" name="video_users[]" multiple>
									<option value="false">Не применять</option>
		@if(isset($availableUsers))
			@foreach($availableUsers as $currentUser)
									<option value="{{ $currentUser->id }}">{{ $currentUser->name }}</option>
			@endforeach
		@endif

								</select>
							</div>
							<div class="col-sm-12 mb-3">
								<h4>Файл видео:</h4>
								<input type="file" name="video_file" />
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="sumbit" class="btn btn-primary" data-id="">Загрузить</button>
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
</style>
@endsection

@section('js')
<script type="text/javascript">
	$('#categories').click(function(){
		window.location.href='{{ route('videocourses.categoriesIndex') }}';
	});
	$('#upload').click(function(){
		$('#uploadErreursMain').css('display', 'none');
		$('#uploadNewVideo').modal('show');
	});
</script>
@endsection
