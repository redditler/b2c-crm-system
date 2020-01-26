@extends('adminlte::page')

@section('content_header')
    <h1 class="content_header__h1">Видео-курсы</h1>
@endsection

@section('content')
	@if(Auth::user()->role_id == 1)
	<button class="btn btn-success" id="upload">Загрузить новое видео</button>
	<button class="btn btn-success" id="categories">Управление разделами</button>
	<button class="btn btn-success" id="videoViews">Просмотры видео</button>
	@endif
	@if(count($videos)>0)
	<div class="row" style="margin-top:25px;">
		@foreach($videos as $thisCatId=>$thisVideo)
		<div class="col-sm-12"><h1><a href="{{ route('videocourses.detailed', $thisCatId) }}">{{ $thisVideo['title'] }}</a></h1></div>
			@foreach($thisVideo['videos'] as $thisVideo)
			<div class="col-md-4 video-panel">
				<div class="panel">
					<div class="panel-head" style="padding-left: 20px;"><h4>{{ $thisVideo->video_title }}</h4></div>
					<div class="panel-body">
				@if(Auth::user()->id == $thisVideo->uploaded_by)
						<video 
							id="video{{ $thisVideo->id }}"
							style="width:100%;" 
							data-id="{{ $thisVideo->id }}" 
							data-title="{{ $thisVideo->video_title }}"
							data-description="{{ str_replace(["\r\n", "\n", "\""], ['{nl}', '{nl}', '{quot}'], $thisVideo->video_description) }}"
							data-category="{{ $thisVideo->category }}"
							data-groups="{{ ($thisVideo->visible_groups == "any" ? 'any' : str_replace('"', '\'', $thisVideo->visible_groups)) }}"
							data-users="{{ ($thisVideo->visible_users == "any" ? 'any' : str_replace('"', '\'', $thisVideo->visible_users)) }}"
							controls
							controlsList="nodownload">
				@else
						<video 
							id="video{{ $thisVideo->id }}"
							style="width:100%;" 
							data-id="{{ $thisVideo->id }}" 
							controls
							controlsList="nodownload">
				@endif
							<source src="{{ $thisVideo->url }}" type="video/mp4">
						</video>
						<div class="row" style="border-bottom:1px dotted #dedede;padding-bottom:5px;color:#8e8e8e;">
							<div class="col-sm-9">
								<table style="width:100%;">
									<tr>
										<td style="width:14px;">
											<i class="fa fa-user-circle-o"></i>
										</td>
										<td style="padding-left:5px;">{{ $thisVideo->uploader }}</td>
									</tr>
								</table>
							</div>
							<div class="col-sm-3" align="right">
								<table>
									<tr>
										<td style="width:14px;">
											<i class="fa fa-eye"></i>
										</td>
				@if((\Illuminate\Support\Facades\Auth::user()->id == 103) || (\Illuminate\Support\Facades\Auth::user()->id == 151))
										<td style="padding-left:5px;">
											<a href="{{ route('videocourses.getViewsByID', $thisVideo->id) }}">{{ $thisVideo->views }}</a>
										</td>
				@else
										<td style="padding-left:5px;">{{ $thisVideo->views }}</td>
				@endif
									</tr>
								</table>
							</div>
						</div>
						<div class="video-description">
							{!! str_replace(["\r\n", "\n"], '<br>', $thisVideo->video_description) !!}
					@if(mb_strlen($thisVideo->video_description)>150)
							<div class="vc-more-text">
								Подробности
							</div>
					@endif
						</div>
					</div>
					<div class="panel-footer">
						<div class="row">
							<div class="col-sm-3">
				@if(Auth::user()->id == $thisVideo->uploaded_by)
								<button class="btn btn-sm btn-default video-edit" data-id="{{ $thisVideo->id }}">
									<i class="fa fa-pencil"></i>
								</button>
				@endif
							</div>
							<div class="col-sm-9" align="right" style="padding-top: 5px;">Загружено {{ $thisVideo->created_at_string }}</div>
						</div>
					</div>
				</div>
			</div>
			@endforeach
		@endforeach
	</div>
	@else
		<div class="alert alert-info" style="margin-top:25px;">
			Нет данных для отображения.
		</div>
	@endif
	@if(Auth::user()->role_id == 1)
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
	@endif
<div id="editVideo" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Правка данных видео</h4>
				<button type="button" class="close vc-modal-close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body vcourses-modal">
			    {{ csrf_field() }}
				<div class="row">
					<div id="editErreursMain" class="alert alert-danger" style="margin-left: 15px;margin-right: 15px;display:none;">
						При загрузке видео произошли некоторые ошибки:
						<ul id="editErreurs"></ul>
					</div>
					<div class="col-sm-12 mb-3">
						<h4>Заголовок видео:</h4>
						<input type="text" id="editVideoTitle" name="video_title" class="form-control">
					</div>
					<div class="col-sm-12 mb-3">
						<h4>Описание видео:</h4>
						<textarea id="editVideoDescription" name="video_description" class="form-control" style="height:120px;"></textarea>
					</div>
					<div class="col-sm-12 mb-3">
						<h4>Раздел для публикации:</h4>
						<select id="editVideoCategory" name="video_category" class="form-control">
	@foreach($categories as $thisCategory)
							<option value="{{ $thisCategory->id }}">{{ $thisCategory->category_title }}</option>
	@endforeach
						</select>
					</div>
					<div class="col-sm-12 mb-3">
						<h4>Область видимости (группы):</h4>
						<select id="editVideoGroupVisiblity" style="width:100%;height:120px;" name="video_groups[]" multiple>
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
						<select id="editVideoUserVisiblity" style="width:100%;height:120px;" name="video_users[]" multiple>
							<option value="false">Не применять</option>
	@if(isset($availableUsers))
		@foreach($availableUsers as $currentUser)
							<option value="{{ $currentUser->id }}">{{ $currentUser->name }}</option>
		@endforeach
	@endif

						</select>
					</div>
					<div class="col-sm-12 mb-3">
						<h4>Удаление видео:</h4>
						<input type="checkbox" id="removeEditedVideo" value="true"> <label for="removeEditedVideo">Удалить данное видео</label><br>
						<span style="font-size:8pt;">Пожалуйста, учтите: видео будет удалено сразу же после отправления формы, предупреждений больше не будет.</span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary save-edited" data-id="">Сохранить изменения</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('css')
<style type="text/css">
	.video-panel {
		min-height: 540px !important;
	}
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
	$('video').on('play', function(){
		if($(this).attr('data-counted') != "true"){
			$(this).attr('data-counted', 'true');
			var viewedLink = '{{ route('videocourses.setViewed', 'null') }}';
			viewedLink = viewedLink.replace('null', $(this).attr('data-id'));
			$.post(
				viewedLink, 
				{ 
					is_viewed: true
				}, 
				function(response) {
					if(response.state == "fail"){
						console.log('Request failed:');
						console.log(response);
					}
				},
				'json'
			);
		}
	});
	$('.save-edited').click(function(){
		$.post(
			'{{ route('videocourses.manage') }}', 
			{ 
				manage_id: 			$(this).attr('data-id'),
				video_title: 		$('#editVideoTitle').val(),
				video_description: 	$('#editVideoDescription').val(),
				video_category: 	$('#editVideoCategory').val(),
				video_groups: 		$('#editVideoGroupVisiblity option:selected').map(function(){ return this.value }).get(),
				video_users: 		$('#editVideoUserVisiblity option:selected').map(function(){ return this.value }).get(),
				remove_flag: 		($('#removeEditedVideo').prop('checked') ? "true" : "false")  		
			}, 
			function(response) {
				if(response.state == "fail"){
					var retnErrors = '';
					Object.keys(response.reason).forEach(function(currentError){
						retnErrors = retnErrors + '<li>' + response.reason[currentError] + '</li>';
					})
					$('#editErreurs').html(retnErrors);
					$('#editErreursMain').fadeIn();
				}else if(response.state == "ok"){
					$('#editErreursMain').fadeOut();
					$('#editVideo').modal('hide');
					location.reload();
				}
			},
			'json'
		);
	})
	$('.video-edit').click(function(){
		$('#editErreursMain').css('display', 'none');
		$('#editVideoTitle').val($('#video' + $(this).attr('data-id')).attr('data-title'));
		$('#editVideoDescription').val($('#video' + $(this).attr('data-id')).attr('data-description').replace(/{quot}/g, '"').replace(/{nl}/g, "\r\n"));
		if($('#video' + $(this).attr('data-id')).attr('data-groups') == "any"){
			var groupValues = 'false';
		}else{
			var groupValues = jQuery.parseJSON($('#video' + $(this).attr('data-id')).attr('data-groups').replace(/'/g, '"'));
			groupValues = groupValues.join(',');
		}
		$('#editVideoCategory').val($('#video' + $(this).attr('data-id')).attr('data-category'));
		$("#editVideoGroupVisiblity option").prop("selected", false);
		$.each(groupValues.split(","), function(i,e){
		    $("#editVideoGroupVisiblity option[value='" + e + "']").prop("selected", true);
		});
		if($('#video' + $(this).attr('data-id')).attr('data-users') == "any"){
			var usersValues = 'false';
		}else{
			var usersValues = jQuery.parseJSON($('#video' + $(this).attr('data-id')).attr('data-users').replace(/'/g, '"'));
			usersValues = usersValues.join(',');
		}
		$("#editVideoUserVisiblity option").prop("selected", false);
		$.each(usersValues.split(","), function(i,e){
		    $("#editVideoUserVisiblity option[value='" + e + "']").prop("selected", true);
		});
		$('.save-edited').attr('data-id', $(this).attr('data-id'));
		$('#editVideo').modal('show');
	});
	$('#upload').click(function(){
		$('#uploadErreursMain').css('display', 'none');
		$('#uploadNewVideo').modal('show');
	});
	$('.vc-more-text').click(function(){
		if($($(this).parent()).css('height') == "120px"){
			$($(this).parent()).css('height', 'auto');
			$(this).text('Скрыть подробности');
		}else{
			$($(this).parent()).css('height', '120px');
			$(this).text('Подробности');
		}
	});
	$('#categories').click(function(){
		window.location.href='{{ route('videocourses.categoriesIndex') }}';
	});
	$('#videoViews').click(function(){
		window.location.href='{{ route('videocourses.getViews') }}';
	});
	@if(isset($returnFails))
		$('#uploadNewVideo').modal('show');
		var returnedFails = '{!! $returnFails !!}';
		if(returnedFails.length>0){
			returnedFails = jQuery.parseJSON(returnedFails);
		}
		var retnErrors = '';
		Object.keys(returnedFails).forEach(function(currentError){
			retnErrors = retnErrors + '<li>' + returnedFails[currentError] + '</li>';
		})
		$('#uploadErreurs').html(retnErrors);
		$('#uploadErreursMain').fadeIn();
	@endif
</script>
@endsection