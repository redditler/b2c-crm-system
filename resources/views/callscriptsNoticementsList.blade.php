@extends('adminlte::page')

@section('content_header')
    <h1 class="content_header__h1">Список заметок</h1>
@endsection

@section('content')
<div class="container" id="mainContainer">
	<div class="panel" style="height:80vh;">
		<div class="row" style="height:100%;">
			<div class="col-md-4" style="border-right: 1px solid #dedede;height:100%;padding-left: 30px;">
				<div class="row" style="position:relative;height:100%;">
					<div class="col-md-12 noticements-main" style="height: calc(100% - 50px);overflow-y: scroll;overflow-x: scroll;padding-top: 10px;"></div>
					<div class="col-md-12" style="margin-top:8px;">
						<button type="button" class="btn btn-success create-category" style="float:left;">Создать раздел</button>
						<button type="button" class="btn btn-success create-noticement" style="float:right;">Создать заметку</button>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<form method="POST" id="noticementForm" style="display: none;">
					{{ csrf_field() }}
					<div class="row" style="margin:15px;">
						<input type="hidden" name="action_type" value="">
						<input type="hidden" name="edit_id" value="">
						<div class="col-md-4">Название заметки:</div>
						<div class="col-md-8">
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='background-color:yellow;'>" data-tag-end="</span>" data-source="edit" data-to="noticementTitle">
								<span class="glyphicon glyphicon-font" style="background-color:yellow;color:black;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:yellow;'>" data-tag-end="</span>" data-source="edit" data-to="noticementTitle">
								<span class="glyphicon glyphicon-font" style="color:yellow;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:blue;'>" data-tag-end="</span>" data-source="edit" data-to="noticementTitle">
								<span class="glyphicon glyphicon-font" style="color:blue;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:green;'>" data-tag-end="</span>" data-source="edit" data-to="noticementTitle">
								<span class="glyphicon glyphicon-font" style="color:green;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:red;'>" data-tag-end="</span>" data-source="edit" data-to="noticementTitle">
								<span class="glyphicon glyphicon-font" style="color:red;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<u>" data-tag-end="</u>" data-source="edit" data-to="noticementTitle">
								<span class="glyphicon glyphicon-text-color"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<i>" data-tag-end="</i>" data-source="edit" data-to="noticementTitle">
								<span class="glyphicon glyphicon-italic"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<b>" data-tag-end="</b>" data-source="edit" data-to="noticementTitle">
								<span class="glyphicon glyphicon-bold"></span>
							</button>
							<input id="noticementTitle" type="text" name="title" class="form-control" style="margin-top:7.5px;">
						</div>
							<div class="col-md-12" style="margin-top:5px;height:5px;border-top:1px dashed #dedede;"></div>
						<div class="col-md-4">Текст заметки:</div>
						<div class="col-md-8">
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='background-color:yellow;'>" data-tag-end="</span>" data-source="edit" data-to="noticementText">
								<span class="glyphicon glyphicon-font" style="background-color:yellow;color:black;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:yellow;'>" data-tag-end="</span>" data-source="edit" data-to="noticementText">
								<span class="glyphicon glyphicon-font" style="color:yellow;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:blue;'>" data-tag-end="</span>" data-source="edit" data-to="noticementText">
								<span class="glyphicon glyphicon-font" style="color:blue;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:green;'>" data-tag-end="</span>" data-source="edit" data-to="noticementText">
								<span class="glyphicon glyphicon-font" style="color:green;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:red;'>" data-tag-end="</span>" data-source="edit" data-to="noticementText">
								<span class="glyphicon glyphicon-font" style="color:red;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<u>" data-tag-end="</u>" data-source="edit" data-to="noticementText">
								<span class="glyphicon glyphicon-text-color"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<i>" data-tag-end="</i>" data-source="edit" data-to="noticementText">
								<span class="glyphicon glyphicon-italic"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<b>" data-tag-end="</b>" data-source="edit" data-to="noticementText">
								<span class="glyphicon glyphicon-bold"></span>
							</button>
							<textarea id="noticementText" class="form-control" name="text" style="height:250px;margin-top:7.5px;"></textarea>
						</div>
							<div class="col-md-12" style="margin-top:5px;height:5px;border-top:1px dashed #dedede;"></div>
						<div class="col-md-4">Раздел:</div>
						<div class="col-md-8">
							<select class="form-control" name="parent_id">
								<option value="-1">Корневой</option>
	@if(isset($getCategories))
		@foreach($getCategories as $thisCategoryID=>$thisCategoryTitle)
								<option value="{{ $thisCategoryID }}">{{ strip_tags($thisCategoryTitle) }}</option>
		@endforeach
	@endif
							</select>
						</div>
							<div class="col-md-12" style="margin-top:5px;height:5px;border-top:1px dashed #dedede;"></div>
						<div class="col-md-4">Область видимости:</div>
						<div class="col-md-8">
							<select class="form-control" name="visiblity[]" style="height:150px;" multiple>
								<option value="any">Без ограничений</option>
	@if($csTopics->count()>0)
		@foreach($csTopics as $thisCsTopic)
								<option value="{{ $thisCsTopic->id }}">{{ $thisCsTopic->topic_name }}</option>
		@endforeach
	@endif
							</select>
						</div>
						<div class="col-md-12 removeBlock" style="margin-top:10px;display:none;border-top:1px dashed #dedede;padding-top:7.5px;">
							<input type="checkbox" value="true" id="removeNoticement" name="remove_unit">
							<label for="removeNoticement">Удалить эту заметку</label><br>
							<span style="font-size:8pt;">Обратите внимание, предупреждений об удалении больше не будет.</span>
						</div>

						<div class="col-md-12" align="center" style="margin-top:25px;">
							<input class="btn btn-default" type="submit" value="Сохранить">
						</div>
					</div>
				</form>
				<form method="POST" id="caterogyForm" style="display: none;">
					{{ csrf_field() }}
					<div class="row" style="margin:15px;">
						<input type="hidden" name="action_type" value="">
						<input type="hidden" name="edit_id" value="">
						<div class="col-md-4">Название раздела:</div>
						<div class="col-md-8">
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='background-color:yellow;'>" data-tag-end="</span>" data-source="edit" data-to="categoryTitle">
								<span class="glyphicon glyphicon-font" style="background-color:yellow;color:black;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:yellow;'>" data-tag-end="</span>" data-source="edit" data-to="categoryTitle">
								<span class="glyphicon glyphicon-font" style="color:yellow;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:blue;'>" data-tag-end="</span>" data-source="edit" data-to="categoryTitle">
								<span class="glyphicon glyphicon-font" style="color:blue;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:green;'>" data-tag-end="</span>" data-source="edit" data-to="categoryTitle">
								<span class="glyphicon glyphicon-font" style="color:green;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<span style='color:red;'>" data-tag-end="</span>" data-source="edit" data-to="categoryTitle">
								<span class="glyphicon glyphicon-font" style="color:red;"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<u>" data-tag-end="</u>" data-source="edit" data-to="categoryTitle">
								<span class="glyphicon glyphicon-text-color"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<i>" data-tag-end="</i>" data-source="edit" data-to="categoryTitle">
								<span class="glyphicon glyphicon-italic"></span>
							</button>
							<button type="button" class="btn btn-sm btn-primary insert-tag format-buttons" data-tag-begin="<b>" data-tag-end="</b>" data-source="edit" data-to="categoryTitle">
								<span class="glyphicon glyphicon-bold"></span>
							</button>
							<input id="categoryTitle" type="text" name="title" class="form-control" style="margin-top:7.5px;">
						</div>
							<div class="col-md-12" style="margin-top:5px;height:5px;border-top:1px dashed #dedede;"></div>
						<div class="col-md-4">Раздел:</div>
						<div class="col-md-8">
							<select class="form-control" name="parent_id">
								<option value="-1">Корневой</option>
	@if(isset($getCategories))
		@foreach($getCategories as $thisCategoryID=>$thisCategoryTitle)
								<option value="{{ $thisCategoryID }}">{{ strip_tags($thisCategoryTitle) }}</option>
		@endforeach
	@endif
							</select>
						</div>
							<div class="col-md-12" style="margin-top:5px;height:5px;border-top:1px dashed #dedede;"></div>
						<div class="col-md-4">Область видимости:</div>
						<div class="col-md-8">
							<select class="form-control" name="visiblity[]" style="height:150px;" multiple>
								<option value="any">Без ограничений</option>
	@if($csTopics->count()>0)
		@foreach($csTopics as $thisCsTopic)
								<option value="{{ $thisCsTopic->id }}">{{ $thisCsTopic->topic_name }}</option>
		@endforeach
	@endif
							</select>
						</div>
						<div class="col-md-12 removeBlock" style="margin-top:10px;display:none;border-top:1px dashed #dedede;padding-top:7.5px;">
							<input type="checkbox" value="true" id="removeCategory" name="remove_unit">
							<label for="removeCategory">Удалить этот раздел</label><br>
							<span style="font-size:8pt;">Обратите внимание, предупреждений об удалении больше не будет, а все разделы и вопросы из удаляемого раздела будут перемещены в корневой.</span>
						</div>

						<div class="col-md-12" align="center" style="margin-top:25px;">
							<input class="btn btn-default" type="submit" value="Сохранить">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection

@section('css')
<link rel="stylesheet" href="/css/jquery.treeview.css" />
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="{{ url('js/jquery.treeview.js') }}"></script>
<script>
	var noticementsSource = '{{ json_encode($getNoticements) }}';
	var noticementsHierarchy = jQuery.parseJSON(noticementsSource.replace(/&quot;/g, '"').replace(/(?:\r\n|\r|\n)/g, '{nl}'));
	$('.noticements-main').append('<ul class="noticements-hierarchy"></ul>');
	if(Object.keys(noticementsHierarchy).length>0){
		$.each(noticementsHierarchy, function(ntcID, ntcContent){
			$.each(ntcContent, function(ntcInID, ntcInContent){
				var endpointLink = '<span style="font-size:8pt;">' + ntcInContent.title.replace(/{quot}/g, '"').replace(/&lt;/g,'<').replace(/&gt;/g, '>') + '</span>';
				if(ntcID == 0){
					$('.noticements-hierarchy').append(`
							<li 
								data-id="${ntcInContent.id}" 
								id="noticement-${ntcInContent.id}" 
								data-content="${ntcInContent.text}" 
								data-type="${(ntcInContent.text !== null ? (ntcInContent.text != '' ? 'noticement' : 'section') : 'section')}"
								data-title="${ntcInContent.title}" 
								data-visiblity="${ntcInContent.visiblity}" 
								data-parent="${ntcInContent.parent_id}">${endpointLink}</li>`);
				}else{
					if($('#noticement-' + ntcInContent.parent_id).children('#ntc-group-' + ntcInContent.parent_id).length > 0){
						$('#ntc-group-' + ntcInContent.parent_id).append(`
							<li 
								data-id="${ntcInContent.id}" 
								id="noticement-${ntcInContent.id}" 
								data-content="${ntcInContent.text}" 
								data-type="${(ntcInContent.text !== null ? 'noticement' : 'section')}"
								data-title="${ntcInContent.title}" 
								data-visiblity="${ntcInContent.visiblity}" 
								data-parent="${ntcInContent.parent_id}">${endpointLink}</li>`);
					}else{
						$('#noticement-' + ntcInContent.parent_id).append(`
							<ul id="ntc-group-${ntcInContent.parent_id}">
								<li 
									data-id="${ntcInContent.id}" 
									id="noticement-${ntcInContent.id}" 
									data-content="${ntcInContent.text}" 
									data-type="${(ntcInContent.text !== null ? 'noticement' : 'section')}"
									data-title="${ntcInContent.title}" 
									data-visiblity="${ntcInContent.visiblity}" 
									data-parent="${ntcInContent.parent_id}">${endpointLink}</li>
							</ul>`);
					}
				}
			});
		});
		$('.noticements-main').on('changed.jstree', function (e, data) {
			if(data.selected.length) {
				var noticementContent = $('#' + data.selected[0]).attr('data-content');
				if($('#' + data.selected[0]).attr('data-type') == "noticement"){
					$('#caterogyForm').css('display', 'none');
					$($('#noticementForm').find('input[name=action_type]')[0]).val('update');
					$($('#noticementForm').find('input[name=edit_id]')[0]).val($('#' + data.selected[0]).attr('data-id'));
					$($('#noticementForm').find('input[name=title]')[0]).val($('#' + data.selected[0]).attr('data-title').replace(/{quot}/g, '"'));
					$($('#noticementForm').find('select[name=parent_id]')[0]).val($('#' + data.selected[0]).attr('data-parent') == 0 ? -1 : $('#' + data.selected[0]).attr('data-parent'));
					var currentVisiblity = $('#' + data.selected[0]).attr('data-visiblity').split(',');
					$.each($($('#noticementForm').find('select[name^=visiblity]')[0]).find('option'), function(objID, objVal){
						if($.inArray($(objVal).val(), currentVisiblity) != -1){
							$(objVal).prop('selected', true);
						}else{
							$(objVal).prop('selected', false);
						}
					});
					$($('#noticementForm').find('textarea[name=text]')[0]).val((typeof(noticementContent) !== "undefined" ? noticementContent.replace(/{nl}/g, "\r\n").replace(/{quot}/g, '"') : noticementContent));
					$('.removeBlock').css('display', '');
					$('#removeNoticement').prop('checked', false);
					$('#noticementForm').fadeIn();
				}else if($('#' + data.selected[0]).attr('data-type') == "section"){
					$('#noticementForm').css('display', 'none');
					$($('#caterogyForm').find('input[name=action_type]')[0]).val('update');
					$($('#caterogyForm').find('input[name=edit_id]')[0]).val($('#' + data.selected[0]).attr('data-id'));
					$($('#caterogyForm').find('input[name=title]')[0]).val($('#' + data.selected[0]).attr('data-title').replace(/{quot}/g, '"'));
					$($('#caterogyForm').find('select[name=parent_id]')[0]).val($('#' + data.selected[0]).attr('data-parent') == 0 ? -1 : $('#' + data.selected[0]).attr('data-parent'));
					var currentVisiblity = $('#' + data.selected[0]).attr('data-visiblity').split(',');
					$.each($($('#caterogyForm').find('select[name^=visiblity]')[0]).find('option'), function(objID, objVal){
						if($.inArray($(objVal).val(), currentVisiblity) != -1){
							$(objVal).prop('selected', true);
						}else{
							$(objVal).prop('selected', false);
						}
					});
					$('.removeBlock').css('display', '');
					$('#removeCategory').prop('checked', false);
					$('#caterogyForm').fadeIn();
				}
			}
		}).jstree();
	}else{
		$('.noticements-main').append('<div class="alert alert-info">Нет данных для отображения</div>');
	}
	$('.create-category').click(function(){
		$('#noticementForm').css('display', 'none');
		$($('#caterogyForm').find('input[name=action_type]')[0]).val('create');				$($('#caterogyForm').find('input[name=edit_id]')[0]).val('');
		$($('#caterogyForm').find('input[name=title]')[0]).val('');							$($('#caterogyForm').find('select[name=parent_id]')[0]).val('-1');
		$($('#caterogyForm').find('select[name^=visiblity]')[0]).val('any');
		$('.removeBlock').css('display', 'none');
		$('#removeCategory').prop('checked', false);
		$('#caterogyForm').fadeIn();
	});
	$('.create-noticement').click(function(){
		$('#caterogyForm').css('display', 'none');
		$($('#noticementForm').find('input[name=action_type]')[0]).val('create');			$($('#noticementForm').find('input[name=edit_id]')[0]).val('');
		$($('#noticementForm').find('input[name=title]')[0]).val('');						$($('#noticementForm').find('select[name=parent_id]')[0]).val('-1');
		$($('#noticementForm').find('select[name^=visiblity]')[0]).val('any');				$($('#noticementForm').find('textarea[name=text]')[0]).val('');
		$('.removeBlock').css('display', 'none');
		$('#removeNoticement').prop('checked', false);
		$('#noticementForm').fadeIn();
	});
	$('.insert-tag').click(function(){
		var qtextEl = $('#' + $(this).attr('data-to'));
        if($(qtextEl).get(0).selectionStart != $(qtextEl).get(0).selectionEnd){
        	var currentValue = $(qtextEl).val();
        	$(qtextEl).val(currentValue.substring(0, $(qtextEl).get(0).selectionStart) + 
        		$(this).attr('data-tag-begin').replace(/'/g, '"') + currentValue.substring($(qtextEl).get(0).selectionStart, $(qtextEl).get(0).selectionEnd) + 
        		$(this).attr('data-tag-end').replace(/'/g, '"') + currentValue.substring($(qtextEl).get(0).selectionEnd));
        }
	});
</script>
@endsection