@extends('adminlte::page')

@section('content')
<style>
	@keyframes spin {
		from {transform:rotate(0deg);}
		to {transform:rotate(360deg);}
	}

</style>
<div id="root"> 
	<div style="display: flex; height: 50vh; align-items: center; justify-content: center; flex-direction: column;">
		<img style="animation: spin 5s linear 0s infinite; width: 100px" src="https://miro.medium.com/max/512/1*jA5lTgPRbyimsFNod7SlFQ.png" alt="">
		<span style=" font-family: Montserrat; font-size: 15px; font-weight: 500; color: #367283; ">Загрузка ... </span>
	</div>
</div>
{{-- 
<div class="__callscript" id="mainContainer" data-call="">
	<div class="row">
		<div class="col-12 dialog-number" style="margin-bottom: 10px; height: 25px;">
	@if((\Illuminate\Support\Facades\Auth::user()->id == 103) || (\Illuminate\Support\Facades\Auth::user()->id == 151))
			<button type="button" class="btn btn-secondary goto-acp-parts" data-page="management/noticements" style="margin: 0;margin-right: 10px;padding: 0px 12px;float:left;">Заметки</button>
			<button type="button" class="btn btn-secondary goto-acp-parts" data-page="management" style="margin: 0;margin-right: 10px;padding: 0px 12px;float:left;">Редактирование</button>
			<button type="button" class="btn btn-secondary goto-acp-parts" data-page="management/list" style="margin: 0;margin-right: 10px;padding: 0px 12px;float:left;position:relative;">
				История диалогов 
				<span class="badge">{{ $logLength }}</span>
			</button>
			<button type="button" class="btn btn-secondary goto-acp-parts" data-page="management/list?type=improvements" style="margin: 0;margin-right: 5px;padding: 0px 12px;float:left;position:relative;">
				Предложения об улучшении 
				<span class="badge">{{ $improvementsQ }}</span>
			</button>
	@endif
			<span class="label label-info" style="float:right;">ID диалога: <span id="dialogue-id">Нет данных</span></span>
		</div>
		<div class="col-12 topics-list mb-3 d-flex justify-content-center" style="margin-bottom:25px;">
	@if($topics->count()>0)
		@foreach($topics as $thisTopic)
			@if(($thisTopic->is_publicated == 1) or (($thisTopic->is_publicated != 1) and ((\Illuminate\Support\Facades\Auth::user()->id == 103) or (\Illuminate\Support\Facades\Auth::user()->id == 151))))
			<button 
				type="button" 
				class="btn btn-secondary topic-selector" 
				id="topic{{ $thisTopic->id }}" 
				data-id="{{ $thisTopic->id }}"
				data-parent="-1"
				style="margin-right:5px;">{{ $thisTopic->topic_name }}</button>
			@endif
		@endforeach
	@else
			<button 
				type="button" 
				class="btn btn-danger"
				disabled>
				Нет доступных тем для диалога
			</button>
	@endif
			<button type="button" class="btn btn-light clear-all" style="float:right;">Очистить диалог</button>
			<button type="button" class="btn btn-light resize-plus" style="float:right;margin-right:3px; padding: 6px 6px;" title="Увеличить размер шрифта">
				<img src="img/icons/pluse.png" alt="">
			</button>
			<button type="button" class="btn btn-light resize-minus" style="float:right;margin-right:3px; padding: 6px 6px;" title="Уменьшить размер шрифта">
				<img src="img/icons/minus.png" alt="">
			</button>
		</div>
		<div class="col-12 questionnaire" data-number="0"></div>
		<div class="col-12" style="height:100px;" id="questionnaireBottom"></div>
	</div>
</div>

<div id="failDescriptionModal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document" style="margin-top: 0;margin-bottom: 0;height: 100vh;display: flex;flex-direction: column;justify-content: center;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Неожиданное течение диалога</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute;right: 15px;top: 15px;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<span style="color:#fff;">Пожалуйста, опишите в нижеследующем поле, что сказал клиент.</span><br><br>
				<textarea id="failDescriptionField" class="form-control" style="height: 250px;"></textarea>
			</div>
			<div class="modal-footer">
				<button id="failDescriptionSave" type="button" class="btn btn-primary">Сохранить</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>

<div id="improveQuestionModal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document" style="margin-top: 0;margin-bottom: 0;height: 100vh;display: flex;flex-direction: column;justify-content: center;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Улучшение элемента диалога</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute;right: 15px;top: 15px;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="improvementErrorBody" class="alert alert-danger" role="alert" style="display:none;">
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					<span id="improvementError">Ошибка</span>
				</div>
				<span style="color:#fff;">Пожалуйста, внесите желаемые исправления в текст этапа диалога.</span><br><br>
				<textarea id="improveQuestionField" class="form-control" style="height: 250px;"></textarea>
			</div>
			<div class="modal-footer">
				<button id="improveQuestionSave" type="button" class="btn btn-primary">Предложить улучшение</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
			</div>
		</div>
	</div>
</div>

<div class="callscriptsDefaultSidebar" style="padding: 12px;right:0;top:85px;z-index:1036;display:none;width: 110px;height:90%;background: #03a5c1;box-shadow: 0 0 5px rgba(0, 0, 0, 0.64);position: fixed;color: white;font-size: 2em;border-radius: 35px 0px 0px 35px;">
	<div class="sidebar-pointer" data-sb-target="quick" style="margin-bottom: 14px;text-align: center;padding: 0px 3px;font-size: 7pt;cursor:pointer;width: 89px;height: 61px;box-shadow: 0 3px 6px rgba(0, 0, 0, 0.45);border-radius: 18px;background-image: linear-gradient(177deg, #e2edf2 0%, #aec4ce 100%);">
		<img src="/img/icons/qestion.png" alt="">
	</div>
	<div class="sidebar-pointer" data-sb-target="noticements" style="margin-bottom: 14px;text-align: center;padding: 8px 3px;font-size: 7pt;cursor:pointer;width: 89px; height: 61px;box-shadow: 0 3px 6px rgba(0, 0, 0, 0.45);border-radius: 18px;background-image: linear-gradient(177deg, #e2edf2 0%, #aec4ce 100%);">
		<img src="/img/icons/book.png" alt="">
	</div>
</div>

<div class="callscriptsOpenedSidebar" style="right:0;top:85px;z-index:1035;display:none;width: 355px;height:90%;background: #03a5c1;box-shadow: 0 0 5px rgba(0, 0, 0, 0.64);position: fixed;color: white;font-size: 2em;border-radius: 35px 0 0 35px;border: 1px solid #6bd8fb;">
	<div style="height: 100%;width: 100%;overflow: hidden;border-radius: 35px 0px 0px 35px;">
		<div style="padding: 15px;overflow-y: auto;height:100%;">
			<div class="panel-group" id="csSidebarBlock">
				<div class="panel panel-default">
					<div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-target="#csQuickToggles" style="cursor:pointer;">
						<h4 class="panel-title">Быстрые переходы</h4>
					</div>
					<div id="csQuickToggles" class="panel-collapse collapse acc-toggles">
						<div id="questionnaireQuickButtons" class="panel-body">
							<span class="questionnaire-info" style="color:#535353;font-size:11pt;">Сначала необходимо выбрать тему диалога</span>
						</div>
					</div>
				</div>
				<div class="panel panel-default" id="noticementsMain">
					<div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-target="#csNoticementsToggle" style="cursor:pointer;position:relative;">
						<h4 class="panel-title">Заметки</h4>
						<button type="button" class="btn btn-light ntc-resize-plus" style="float:right;margin-right:3px; padding: 6px 6px;position: absolute;top: 1px;right: 40px;" title="Увеличить размер шрифта">
							<img src="img/icons/pluse.png" alt="">
						</button>
						<button type="button" class="btn btn-light ntc-resize-minus" style="float:right;margin-right:3px; padding: 6px 6px;position: absolute;top: 1px;right: 8px;" title="Уменьшить размер шрифта">
							<img src="img/icons/minus.png" alt="">
						</button>
					</div>
					<div id="csNoticementsToggle" class="panel-collapse collapse acc-toggles">
						<div class="noticement-content alert alert-info" style="display: none;font-size: 9pt;margin:15px 7.5px -5px 7.5px;text-align:justify;background-color:#0aa3c4 !important;"></div>
						<div id="noticementsSection" class="noticements-main panel-body" style="color:#535353;font-size:11pt;overflow-y: auto;overflow-x: scroll; align-items: flex-start;margin-left:-10px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="cs-sidebar-toggle">
		<span><i class="fa fa-fw fa-times"></i></span>
	</div>
</div> --}}
{{-- <div></div> --}}
{{-- <div id="root"></div> --}}
@endsection

{{-- @section('css')
<link rel="stylesheet" href="css/jquery.treeview.css" />
@endsection --}}

@section('js')
{{-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="{{ url('js/jquery.sidebar.min.js') }}"></script>
<script src="{{ url('js/jquery.treeview.js') }}"></script> --}}

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
{{-- <script src="https://unpkg.com/react@16/umd/react.production.min.js"></script>
<script src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js"></script> --}}
<script src="https://unpkg.com/react@16/umd/react.development.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js" crossorigin></script>
<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

<script type="text/babel" src="{{asset('js/CallScriptApp.js')}}"></script>

<script type="text/babel">
	ReactDOM.render( <CallScriptApp />, document.getElementById('root') );
	
	// class App extends React.Component{
	// 	constructor(props){
	// 		super(props);

	// 		this.state = {
	// 			themes: [],
	// 			history: [],
	// 			question: [],
	// 			additionalQuestion: [],
	// 			notes: [],
	// 		}

	// 		this.handleClickTheme = this.handleClickTheme.bind(this);
	// 		this.hendeClickAnswer = this.hendeClickAnswer.bind(this);
	// 	}

	// 	componentDidMount(){
	// 		axios.get('/callscripts/getTopicCall')
	// 			.then( ( response ) => { this.setState({ themes: response.data.topics }) } );	

	// 		axios({
	// 			method: 'post',
	// 			url: '/callscripts/noticements',
	// 			data: { topic: 'any' }
	// 		}).then( (response) => { 
	// 			this.setState( { additionalQuestion: response.data } ) 
	// 			console.log(response.data);
	// 		} );
	// 	}
		
		
	// 	handleClickTheme(e) {
	// 		console.log(e);
	// 		let data = {
	// 			topic: e.props.id,
	// 			parent_id: -1,
	// 			call_id: 1580451519048
	// 		}
	// 		axios({
	// 			method: 'post',
	// 			url: '/callscripts/questionnaire',
	// 			data: data,
	// 		}).then( (response) => { 
	// 			this.setState({history: [{id: response.data.id, autor: 'manager', message: response.data.question_text}] });
	// 			this.setState({question: response.data.variants});
	// 			console.log(this.state)
	// 		} );
	// 	}

	// 	hendeClickAnswer(e){
	// 		console.log(e);

	// 		let data = {
	// 			topic: e.props.id,
	// 			parent_id: -1,
	// 			call_id: 1580451519048
	// 		}

	// 		axios({
	// 			method: 'post',
	// 			url: '/callscripts/questionnaire',
	// 			data: data,
	// 		}).then( (response) => { 
	// 			this.setState({history: [{id: response.data.id, 
		// autor: 'manager', 
		// message: response.data.question_text}] });
	// 			this.setState({question: response.data.variants});
	// 			console.log(this.state)
	// 		});
	// 	}


	// 	render(){
	// 		return(
	// 			<div className="callscript">
	// 				<div className="callscript__header">
	// 					{this.state.themes.map(item=>(
	// 						<ThemeElement key={item.id} id={item.id} title={item.topic_name} event={this.handleClickTheme}/>
	// 					))}
	// 				</div>
	// 				<div className="callscript__body">
	// 					{this.state.history.map(item=>(
	// 						<HistoryElement key={item.id} autor={item.autor} message={item.message}/>
	// 					))}
	// 				</div>
	// 				<div className="callscript__footer">
	// 					<div className="callscript__main-question">
	// 						{this.state.question.map(item=>(
	// 							<MenuElement key={item.id} type={item.type} title={item.title} event={this.hendeClickAnswer}/>
	// 						))}
	// 					</div>
	// 					<div className="callscript__additional-question">
	// 						{
	// 						// 	this.state.additionalQuestion.map(item=>(
	// 						// 	<MenuElement key={item.id} type={item.type} title={item.title} event={this.hendeClickAnswer}/>
	// 						// ))
	// 						}
	// 					</div>
	// 				</div>
	// 			</div>
	// 		)
	// 	}
	// }

	// class ThemeElement extends React.Component{
	// 	render(){
	// 		return(
	// 			<button onClick={() => this.props.event(this)} className="btn btn-theme" data-id={this.props.id} data-parent="-1">
	// 				<span className="theme__content">{this.props.title}</span>
	// 			</button>
	// 		)
	// 	}
	// }


	// class HistoryElement extends React.Component{
	// 	render(){
	// 		return(
	// 			<div className="message">
	// 				<div className="message__header">{this.props.autor}</div>
	// 				<div className="message__body">
	// 					<div className="message__content">{this.props.message}</div>
	// 				</div>
	// 				<div className="message__footer"></div>
	// 			</div>
	// 		)
	// 	}
	// } 

	// class MenuElement extends React.Component{
	// 	render(){
	// 		return(
	// 			<button onClick={() => this.props.event(this)} className="answer" data-type={this.props.type} >
	// 				<span className="answer__content">{this.props.title}</span>
	// 			</button>
	// 		)
	// 	}
	// }

	

	
</script>

<script>

	

	

	// $('.accordion-toggle').click(function(event){
	// 	event.preventDefault();
	//     event.stopPropagation();
	// 	var restOfAccordions = '.acc-toggles:not(' + $(this).attr('data-target') + ')';
	// 	if($($(this).attr('data-target')).css('display') == "none"){
	// 		$($(this).attr('data-target')).collapse('show');
	// 		$(restOfAccordions).collapse('hide');
	// 	}else{
	// 		$($(this).attr('data-target')).collapse('hide');
	// 	}
	// });
	// const panelCounter = () => ++($('.questionnaire .panel:visible').length);
	// $('.callscriptsOpenedSidebar').sidebar({side: 'right'});
	// $('.callscriptsDefaultSidebar').sidebar({side: 'right'});
	// /*
	// 	Следующая строка препятствует отображению сайдбара до того, как он скроется
	// */
	// $('.callscriptsOpenedSidebar,.callscriptsDefaultSidebar').css('display', '');
	// $('.callscriptsDefaultSidebar').trigger('sidebar:open');
	// $('.cs-sidebar-toggle,.sidebar-pointer').click(function(){
	// 	if($(this).hasClass('sidebar-pointer')){
	// 		if($(this).attr('data-sb-target') == "noticements"){
	// 			$('#csQuickToggles').collapse('hide');
	// 			$('#csNoticementsToggle').collapse('show');
	// 		}else if($(this).attr('data-sb-target') == "quick"){
	// 			$('#csNoticementsToggle').collapse('hide');
	// 			$('#csQuickToggles').collapse('show');
	// 		}
	// 	}
	// 	$('.callscriptsOpenedSidebar,.callscriptsDefaultSidebar').trigger('sidebar:toggle');
	// });
	// $('.ntc-resize-plus,.ntc-resize-minus').click(function(event){
	// 	event.preventDefault();
	//     event.stopPropagation();
	// 	if($(this).hasClass('ntc-resize-plus')){
	// 		var changedSize = 'plus';
	// 	}else if($(this).hasClass('ntc-resize-minus')){
	// 		var changedSize = 'minus';
	// 	}else{
	// 		return false;
	// 	}
	// 	Object.keys(document.styleSheets).forEach(function(current){
	// 		if(document.styleSheets[current].href === null){
	// 			Object.keys(document.styleSheets[current].rules).forEach(function(thisRule){
	// 				if(document.styleSheets[current].rules[thisRule].selectorText == ".ntc-ptr"){
	// 					var newNtcTextSize = (changedSize == "plus" ? parseInt(document.styleSheets[current].rules[thisRule].style.fontSize.replace('pt', ''))+3 : parseInt(document.styleSheets[current].rules[thisRule].style.fontSize.replace('pt', ''))-3) + 'pt';
	// 					document.styleSheets[current].rules[thisRule].style.fontSize = newNtcTextSize;
	// 					$('.noticement-content').css('font-size', newNtcTextSize);
	// 				}
	// 			});
	// 		}
	// 	});
	//     return false;
	// });
	// function getNoticements(topic='any'){
	// 	$('#noticementsSection').jstree('destroy');
	// 	$('#noticementsSection').empty();
	// 	$.post(
	// 		'{{ route('callscriptsGetNoticements') }}', 
	// 		{ 
	// 			topic: topic 
	// 		}, 
	// 		function(response) {
	// 			if(Object.keys(response).length>0){
	// 				$('.noticements-main').append('<ul class="noticements-hierarchy" style="padding-right: 10px;"></ul>');
	// 				$.each(response, function(ntcID, ntcContent){
	// 					$.each(ntcContent, function(ntcInID, ntcInContent){
	// 						var endpointLink = '<span class="ntc-ptr">' + ntcInContent.title.replace(/{quot}/g, '"').replace(/&lt;/g,'<').replace(/&gt;/g, '>') + '</span>';
	// 						if(ntcID == 0){
	// 							$('.noticements-hierarchy').append(`
	// 									<li 
	// 										data-id="${ntcInContent.id}" 
	// 										id="noticement-${ntcInContent.id}" 
	// 										data-title="${ntcInContent.title}" 
	// 										data-content="${(ntcInContent.text !== null ? ntcInContent.text.replace(/(?:\r\n|\r|\n)/g, '<br>') : ntcInContent.text)}">
	// 										${endpointLink}
	// 									</li>`);
	// 						}else{
	// 							if($('#noticement-' + ntcInContent.parent_id).children('#ntc-group-' + ntcInContent.parent_id).length > 0){
	// 								$('#ntc-group-' + ntcInContent.parent_id).append(`
	// 									<li 
	// 										data-id="${ntcInContent.id}" 
	// 										id="noticement-${ntcInContent.id}" 
	// 										data-title="${ntcInContent.title}" 
	// 										data-content="${(ntcInContent.text !== null ? ntcInContent.text.replace(/(?:\r\n|\r|\n)/g, '<br>') : ntcInContent.text)}">
	// 										${endpointLink}
	// 									</li>`);
	// 							}else{
	// 								$('#noticement-' + ntcInContent.parent_id).append(`
	// 									<ul id="ntc-group-${ntcInContent.parent_id}">\
	// 										<li 
	// 											data-id="${ntcInContent.id}" 
	// 											id="noticement-${ntcInContent.id}" 
	// 											data-title="${ntcInContent.title}" 
	// 											data-content="${(ntcInContent.text !== null ? ntcInContent.text.replace(/(?:\r\n|\r|\n)/g, '<br>') : ntcInContent.text)}">
	// 											${endpointLink}
	// 										</li>
	// 									</ul>`);
	// 							}
	// 						}
	// 					});
	// 				});
	// 				$('.noticements-hierarchy').append(`
	// 					<li 
	// 						data-id="-1" 
	// 						class="link-noticement" 
	// 						id="noticement-specific-1" 
	// 						data-content="{{ url('/uploads/6bbb3401316f4b68f99e1e0f4d85a5db.pdf') }}">
	// 							<span class="ntc-ptr">
	// 								<strong>Ассортимент Steko 2020</strong>
	// 							</span>
	// 						</li>`);
	// 				$('#noticementsSection').on('changed.jstree', function (e, data) {
	// 					if(data.selected.length) {
	// 						var noticementContent = $('#' + data.selected[0]).attr('data-content');
	// 						if(noticementContent != 'null' && typeof(noticementContent) != "undefined"){
	// 							if($('#' + data.selected[0]).hasClass('link-noticement')){
	// 								$('.noticement-content').fadeOut();
	// 								var targetTab = window.open($('#' + data.selected[0]).attr('data-content'), '_blank');
	// 								if (targetTab) {
	// 									targetTab.focus();
	// 								} else {
	// 									alert('Сначала лучше бы разрешить всплывающие окна для данного веб-узла');
	// 								}
	// 							}else{
	// 								$('.noticement-content').html(`<strong>${$('#' + data.selected[0]).attr('data-title').replace(/{quot}/g, '"')}</strong><br><br>${noticementContent.replace(/{quot}/g, '"')}`).fadeIn();
	// 							}
	// 						}else{
	// 							$('.noticement-content').fadeOut();
	// 						}
	// 					}
	// 				}).jstree();
	// 			}else{
	// 				$('.noticements-main').append('<div class="alert alert-info">Нет данных для отображения</div>');
	// 			}
	// 		},
	// 		'json'
	// 	);
	// }
	// var d = new Date();
	// $('#mainContainer').attr('data-call', d.getTime());
	// $('#dialogue-id').text($('#mainContainer').attr('data-call'));
	// $.ajaxSetup({headers: {'X-CSRF-TOKEN': CONFIG_JS.csrfToken}});
	// function parseBtnSizes(){
	// 	var parsedBtnSize = $($('.questionnaire').find('.btn')[0]).css('font-size');
	// 	var parsedTxtSize = $($('.questionnaire').find('.panel-body')[0]).css('font-size');
	// 	$('.questionnaire,#questionnaireQuickButtons').find('.btn').css('font-size', parsedBtnSize);
	// 	$('.questionnaire').find('.goto-management').css('font-size', parsedBtnSize);
	// 	$('.questionnaire').find('.panel-body').css('font-size', parsedTxtSize, 'important');
	// }
	// $('button[class^=\'resize-\'],button[class*=\' resize-\']').click(function(){
	// 	if($(this).hasClass('resize-plus')){
	// 		var changedSize = '+=3';
	// 	}else if($(this).hasClass('resize-minus')){
	// 		var changedSize = '-=3';
	// 	}else{
	// 		return true;
	// 	}
	// 	$('.questionnaire,.panel-body').css('font-size', 	changedSize, 'important');
	// 	$('.questionnaire,#questionnaireQuickButtons')
	// 		.find('.btn').css('font-size', 					changedSize, 'important');
	// 	parseBtnSizes();
	// });
	// $('.topic-selector').click(function() {
	// 	$('#mainContainer').attr('data-topic', $(this).attr('data-id'));
	// 	var currentPostParams = { 
	// 		topic: 		$(this).attr('data-id'),
	// 		parent_id: 	$(this).attr('data-parent'),
	// 		call_id: 	$('#mainContainer').attr('data-call')
	// 	};
	// 	getNoticements($(this).attr('data-id'));
	// 	$.post(
	// 		'{{ route('callscriptsGetQuestion') }}', 
	// 		currentPostParams, 
	// 		function(response) {
	// 			if(response.has_response){
	// 				var variantButtons = '';
	// 				if(response.variants !== null){
	// 					for(var thisVariant=0; thisVariant<response.variants.length; thisVariant++){
	// 						variantButtons = variantButtons + '<button \
	// 			type="button" \
	// 			class="btn';
	// 						if(typeof response.variants[thisVariant].type  !== "undefined"){
	// 							if(response.variants[thisVariant].type == 1){
	// 								variantButtons = variantButtons + ' btn-default';
	// 							}else if(response.variants[thisVariant].type == 2){
	// 								variantButtons = variantButtons + ' btn-success';
	// 							}else if(response.variants[thisVariant].type == 3){
	// 								variantButtons = variantButtons + ' btn-info';
	// 							}else if(response.variants[thisVariant].type == 4){
	// 								variantButtons = variantButtons + ' btn-warning';
	// 							}
	// 						}else{
	// 							variantButtons = variantButtons + ' btn-default';
	// 						}
	// 						variantButtons = variantButtons + ' question-answer" \
	// 			id="topic' + $('#mainContainer').attr('data-topic') + '" \
	// 			data-id="' + response.variants[thisVariant].link + '" \
	// 			data-parent="' + response.id + '" \
	// 			data-button="' + response.variants[thisVariant].id + '" \
	// 			style="margin-right:5px;position:relative;';
	// 						if(typeof response.variants[thisVariant].type  !== "undefined"){
	// 							if(response.variants[thisVariant].type == 3){
	// 								variantButtons = variantButtons + 'width:auto;';
	// 							}
	// 						}
	// 						variantButtons = variantButtons + '">' + response.variants[thisVariant].title + '</button>'
	// 					}
	// 				}
	// 				var updatePhase = parseInt($('.questionnaire').attr('data-number')) + 1;
	// 				$('.questionnaire').attr('data-number', updatePhase);
	// @if((\Illuminate\Support\Facades\Auth::user()->id == 103) || (\Illuminate\Support\Facades\Auth::user()->id == 151))
	// 				var moderateurLink = '<div class="open-construct"><span class="label label-info goto-management" style="cursor:pointer;" data-id="' + response.id + '">Открыть в конструкторе</span></div>';
	// @else
	// 				var moderateurLink = '';
	// @endif
	// 				var questionParts = response.question_text.split('{%break%}');
	// 				var additionalParts = '';
	// 				if(questionParts.length>1){
	// 					for(var partIdx=0; partIdx<questionParts.length; partIdx++){
	// 						if(partIdx>0){
	// 							additionalParts = additionalParts + '<hr>' + $(questionParts).get(partIdx);
	// 						}
	// 					};
	// 				}
	// 				$('.questionnaire').append(`
	// 									<div class="col-12 mb-3 question-ident" id="question${response.id}-${response.occurence_id}" data-phase="${updatePhase}">
	// 										<div class="panel">
	// 											<div class="panel-wrapper">
	// 												<div class="panel-content">
	// 													<div class="panel-icon">
	// 														<span>${panelCounter()}</span>
	// 													</div>
	// 													<div class="panel-heading">
	// 														<span class="question--title">${response.question_title}</span>
	// 													</div>
	// 													<div class="panel-body">
	// 														${ questionParts[0] + (response.instructions != null ? '<hr><i>' + response.instructions + '</i>' : '') + additionalParts}
	// 													</div>
	// 													<div class="panel-footer">
	// 														${moderateurLink}
	// 														<button type="button" data-source="${response.id}" data-occurence-id="${response.occurence_id}" class="btn btn-light question-cancel" data-type="question">Отмена</button>
	// 													</div>
	// 												</div>
	// 											</div>
	// 											<div class="panel-ansver">
	// 												${variantButtons}
	// 											</div>
	// 										</div>
	// 									</div>`);

	// 				if(typeof(response.quick) != "undefined" && response.quick !== null) {
	// 					$('#questionnaireQuickButtons>button').fadeOut();
	// 					$(response.quick).each(function( qqIndex, qqValue ) {
	// 						if(qqValue.title.length>24 && false){
	// 							var printQuickTitle = '<marquee scrolldelay="150">' + qqValue.title + '</marquee>';
	// 						}else{
	// 							var printQuickTitle = qqValue.title;
	// 						}
	// 						$('#questionnaireQuickButtons').append(`<button type="button" class="btn btn-default question-answer" id="topic${currentPostParams.topic}" data-id="${qqValue.id}" data-parent="${currentPostParams.parent_id}" data-noreplace="true" style="margin-bottom:3.5px;">${printQuickTitle}</button>`);
	// 					});
	// 					parseBtnSizes();
	// 					$('.questionnaire-info').text('');
	// 				}else{
	// 					$('#questionnaireQuickButtons>button').fadeOut();
	// 					$('.questionnaire-info').text('Данная тема не предусматривает быстрых переходов, простите великодушно.');
	// 				}
	// 			}else{
	// 				$('.questionnaire').append('\
	// 					<div class="col-12 mb-3">\
	// 						<div class="alert alert-warning">Нет продолжения для данного диалога.</div>\
	// 					</div>');
	// 			}
	// 		},
	// 		"json"
	// 	);
	// });
	// $('.clear-all').click( function () {
	// 	var d = new Date();
	// 	$('.questionnaire').empty();
	// 	$('#mainContainer').attr('data-call', d.getTime());
	// 	$('#dialogue-id').text($('#mainContainer').attr('data-call'));
	// });
	// $(document).on('click', '.question-cancel', function() {
	// 	if($(this).attr('data-type') == "message"){
	// 		$('#' + $(this).attr('data-type') + $(this).attr('data-occurence-id')).fadeOut();
	// 	}else{
	// 		$('#' + $(this).attr('data-type') + $(this).attr('data-source') + '-' + $(this).attr('data-occurence-id')).fadeOut();
	// 	}
	// 	$.post(
	// 		'{{ route('callscriptsRemoveAnswer') }}', 
	// 		{ 
	// 			occurence_id: 	$(this).attr('data-occurence-id'),
	// 			call_id: 		$('#mainContainer').attr('data-call'), 
	// 		}, 
	// 		function(response) {
	// 			if(response.state == "fail"){
	// 				console.log('Request failed:');
	// 				console.log(response);
	// 			}
	// 		},
	// 		'json'
	// 	);
	// });
	// $(document).on('click', '.question-improve', function() {
	// 	$('#improveQuestionField').attr('data-id', $(this).attr('data-source'));
	// 	var sourceQuestionText = $($('#question' + $(this).attr('data-source') + '-' + $(this).attr('data-occurence-id')).find('.panel-body')[0]).html().replace(/\t/g, '');
	// 	$('#improveQuestionField').val(sourceQuestionText.replace(/<hr><i>[\s\S]*?<\/i>/, ''));
	// 	$('#improveQuestionModal').modal();
	// });
	// $('#improveQuestionSave').click(function(){
	// 	if($('#improveQuestionField').val().length>0){
	// 		$.post(
	// 			'{{ route('callscriptsImproveQuestion') }}', 
	// 			{ 
	// 				question_id: 	$('#improveQuestionField').attr('data-id'),
	// 				question_text: 	$('#improveQuestionField').val()
	// 			}, 
	// 			function(response) {
	// 				if(response.state == "fail"){
	// 					$('#improvementError').text(response.description);
	// 					$('#improvementErrorBody').fadeIn();
	// 				}else{
	// 					$('#improvementErrorBody').fadeOut();
	// 					$('#improveQuestionModal').modal('hide');
	// 				}
	// 			},
	// 			'json'
	// 		);
	// 	}
	// });
	// $(document).on('click', '.question-fail-describe', function() {
	// 	$('#failDescriptionSave').attr('data-occurence-id', $(this).attr('data-source'));
	// 	$('#failDescriptionModal').modal();
	// });
	// $('#failDescriptionSave').click(function(){
	// 	if($('#failDescriptionField').val().length>0){
	// 		$.post(
	// 			'{{ route('callscriptsDescribeFailure') }}', 
	// 			{ 
	// 				occurence_id: 	$(this).attr('data-occurence-id'),
	// 				call_id: 		$('#mainContainer').attr('data-call'), 
	// 				description: 	$('#failDescriptionField').val() 
	// 			}, 
	// 			function(response) {
	// 				if(response.state == "fail"){
	// 					console.log('Request failed:');
	// 					console.log(response);
	// 				}else{
	// 					$('#failDescriptionModal').modal('hide');
	// 				}
	// 			},
	// 			'json'
	// 		);
	// 	}
	// });
	// @if((\Illuminate\Support\Facades\Auth::user()->id == 103) || (\Illuminate\Support\Facades\Auth::user()->id == 151))
	// $(document).on('click', '.goto-management', function () {
	// 	var managementDirected = '{{ route('callscriptsManagementDirected', 'stub') }}';
	// 	var managementWindow = window.open(managementDirected.replace('stub', $(this).attr('data-id')), '_blank');
	// 	if(managementWindow) {
	// 	    managementWindow.focus();
	// 	}else{
	// 	    alert('Пожалуйста, разрешите всплывающие окна для данного ресурса.');
	// 	}
	// });
	// $('.goto-acp-parts').click(function(){
	// 	var baseRoute = '{{ route('callscriptsBegin') }}';
	// 	window.location.href = baseRoute + '/' + $(this).attr('data-page');
	// });
	// @endif
	// $(document).on('click', '.question-answer', function () {
	// 	var isDialogueFinished = false;
	// 	var maxPhaseToAbort = $(this).parent().parent().parent().attr('data-phase');
	// 	var getNearestPath = $('div[class^=answer-path-],div[class*=\' answer-path-\']').filter(function() {
	// 	    return  $(this).attr('data-phase') > maxPhaseToAbort;
	// 	});
	// 	$(getNearestPath).fadeOut();
	// 	if($(this).attr('data-parent') != '-1'){
	// 		$(this).html($(this).html() + '<div class="badge badge-info"><i class="fa fa-check"></i></div>');
	// 	}
	// 	var updatePhase = parseInt($('.questionnaire').attr('data-number')) + 1;
	// 	$('.questionnaire').attr('data-number', updatePhase);
	// 	if($(this).attr('data-id') == -1){
	// 		$('.questionnaire').append(`<div class="col-12 mb-3 answer-path-0" data-phase="${updatePhase}">
	// 			<div class="alert alert-danger alert-wrapper">
	// 				<div class="alert-content">
	// 					<div class="panel-icon"></div>
	// 					<p class="alert-text">Не успешное завершение разговора</p>
	// 				</div>
	// 			</div>
	// 		</div>`);
	// 		isDialogueFinished = true;
	// 	}else if($(this).attr('data-id') == -2){
	// 		$('body').fireworks();
    // 		jQuery('body').append(jQuery('canvas').addClass('cs-greetings'));
	// 		$('.questionnaire').append(`<div class="col-12 mb-3 answer-path-0" data-phase="${updatePhase}">
	// 							<div class="alert alert-success alert-wrapper">
	// 									<div class="alert-content">
	// 										<div class="panel-icon"></div>
	// 										<p class="alert-text">Успешное завершение разговора, поздравляем.</p>
	// 									</div>
	// 								</div>
	// 							</div>`);
	// 		isDialogueFinished = true;
	// 	}
	// 	$.post(
	// 		'{{ route('callscriptsGetQuestion') }}', 
	// 		{ 
	// 			topic: 		$('#mainContainer').attr('data-topic'),
	// 			parent_id: 	$(this).attr('data-parent'),
	// 			answered: 	$(this).attr('data-button'), 
	// 			next_id: 	$(this).attr('data-id'),
	// 			call_id: 	$('#mainContainer').attr('data-call'), 
	// 		}, 
	// 		function(response) {
	// 			if(response.has_response){
	// 				var variantButtons = '';
	// 				if(response.variants !== null){
	// 					for(var thisVariant=0; thisVariant<response.variants.length; thisVariant++){
	// 						variantButtons = variantButtons + '<button \
	// 			type="button" \
	// 			class="btn';
	// 						if(typeof response.variants[thisVariant].type  !== "undefined"){
	// 							if(response.variants[thisVariant].type == 1){
	// 								variantButtons = variantButtons + ' btn-default';
	// 							}else if(response.variants[thisVariant].type == 2){
	// 								variantButtons = variantButtons + ' btn-success';
	// 							}else if(response.variants[thisVariant].type == 3){
	// 								variantButtons = variantButtons + ' btn-info';
	// 							}else if(response.variants[thisVariant].type == 4){
	// 								variantButtons = variantButtons + ' btn-warning';
	// 							}
	// 						}else{
	// 							variantButtons = variantButtons + ' btn-default';
	// 						}
	// 						variantButtons = variantButtons + ' question-answer" \
	// 			id="topic' + $('#mainContainer').attr('data-topic') + '" \
	// 			data-id="' + response.variants[thisVariant].link + '" \
	// 			data-parent="' + response.id + '" \
	// 			data-button="' + response.variants[thisVariant].id + '" \
	// 			style="margin-right:5px;position:relative;';
	// 						if(typeof response.variants[thisVariant].type  !== "undefined"){
	// 							if(response.variants[thisVariant].type == 3){
	// 								variantButtons = variantButtons + 'width:auto;';
	// 							}
	// 						}
	// 						variantButtons = variantButtons + '">' + response.variants[thisVariant].title + '</button>'
	// 					}
	// 					variantButtons = variantButtons + '<button type="button" class="btn btn-sm btn-default question-fail-describe" data-source="' + response.occurence_id + '" style="float:right;">Предложить свой вариант</button>';
	// 				}
	// @if((\Illuminate\Support\Facades\Auth::user()->id == 103) || (\Illuminate\Support\Facades\Auth::user()->id == 151))
	// 				var moderateurLink = '<div class="open-construct"><span class="label label-info goto-management" style="cursor:pointer;" data-id="' + response.id + '">Открыть в конструкторе</span></div>';
	// @else
	// 				var moderateurLink = '';
	// @endif
	// 				var questionParts = response.question_text.split('{%break%}');
	// 				var additionalParts = '';
	// 				if(questionParts.length>1){
	// 					for(var partIdx=0; partIdx<questionParts.length; partIdx++){
	// 						if(partIdx>0){
	// 							additionalParts = additionalParts + '<hr>' + $(questionParts).get(partIdx);
	// 						}
	// 					};
	// 				}
	// 				$('.questionnaire').append(`
	// 									<div class="col-12 mb-3 question-ident answer-path-${response.id}" id="question${response.id}-${response.occurence_id}" data-phase="${updatePhase}">
	// 										<div class="panel">
	// 											<div class="panel-wrapper">
	// 												<div class="panel-content">
	// 													<div class="panel-icon">
	// 														<span>${panelCounter()}</span>
	// 													</div>
	// 													<div class="panel-heading">
	// 														<span class="question--title">${response.question_title}</span>
	// 													</div>
	// 													<div class="panel-body">
	// 														${ questionParts[0] + (response.instructions != null ? '<hr><i>' + response.instructions + '</i>' : '') + additionalParts}
	// 													</div>
	// 													<div class="panel-footer">
	// 														${moderateurLink}
	// 														<div class="btn-area">
	// 															<button type="button" data-source="${response.id}" data-occurence-id="${response.occurence_id}" class="btn btn-sm btn-light question-improve" data-type="question">Улучшить</button>
	// 															<button type="button" data-source="${response.id}" data-occurence-id="${response.occurence_id}" class="btn btn-light question-cancel" data-type="question">Отмена</button>
	// 														</div>
	// 													</div>
	// 												</div>
	// 											</div>
	// 											<div class="panel-ansver">
	// 												${variantButtons}
	// 											</div>
	// 										</div>
	// 									</div>`);
	// 			}else{
	// 				if(isDialogueFinished == false){
	// 					$('.questionnaire').append(`
	// 										<div class="col-12 mb-3 answer-path-0" id="message${response.occurence_id}" data-phase="${updatePhase}">
	// 											<div class="alert alert-warning alert-wrapper">
	// 												<div class="alert-content">
	// 													<div class="panel-icon"></div>
	// 													<p class="alert-text">Нет продолжения для данного диалога.</p>
	// 													<div class="btn-area">
	// 														<button class="btn btn-warning question-fail-describe" data-source="${response.occurence_id}">Уточнить подробности</button>
	// 														<button class="btn btn-warning question-cancel" data-source="${response.occurence_id}" data-occurence-id="${response.occurence_id}" data-type="message">Отмена</button>
	// 													</div>
	// 												</div>

	// 											</div>
	// 										</div>`);
	// 				}
	// 			}
	// 			$("body,html").animate({
	// 				scrollTop: $('div[data-phase=' + updatePhase + ']').offset().top
	// 				},800);
	// 			parseBtnSizes();
	// 		},
	// 		'json'
	// 	);
	// 	$('.callscriptsOpenedSidebar').trigger('sidebar:close');
	// 	$('.callscriptsDefaultSidebar').trigger('sidebar:open');
	// });
	// getNoticements('any');

	// $(document).ready(function() {
	// 	$('body').on('click', '.cs-greetings', function() {
	// 		$('body').fireworks('destroy');  
	// 	}); 
	// });
</script>
@endsection
