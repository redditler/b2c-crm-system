@extends('adminlte::page')
@section('content')
    <div id='events_block'>
	    <div class="box box-solid">
            <div class="box-header with-border">
                <h4 class="box-title">Заметки</h4>
            </div>
            <div class="box-body">
                <!-- the events -->
                <div id="external-events">
	                <div class="external-event bg-green ui-draggable ui-draggable-handle" style="position: relative; background-color: #00A65A; ">Перезвонить</div>
	                <div class="external-event bg-yellow ui-draggable ui-draggable-handle" style="position: relative; background-color: #f39c12;">Просчитать</div>
	                <div class="external-event bg-aqua ui-draggable ui-draggable-handle" style="position: relative; background-color: #00c0ef;">Встреча</div>
	                <div class="checkbox">
	                    <label for="drop-remove">
	                    	<input type="checkbox" id="drop-remove">
	                    	удалить заметку после перемещения
	                    </label>
	                </div>
                </div>
            </div>
        </div>

	    <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Создать заметку</h3>
            </div>
            <div class="box-body">
                <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
	                <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
	                <ul class="fc-color-picker" id="color-chooser">
	                    <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
	                    <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
	                    <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
	                    <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
	                    <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
	                    <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
	                    <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
	                    <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
	                    <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
	                    <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
	                    <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
	                    <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
	                    <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
	                </ul>
                </div>
                <div class="input-group">
	                <input id="new-event" type="text" class="form-control" placeholder="имя заметки">
	                <div class="input-group-btn">
	                    <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Add</button>
	                </div>
                </div>
            </div>
         </div>
	</div>
	<div id='calendar-container'>
	    <div id='calendar'></div>
	</div>
@endsection

@section('tmp_js')
	<script>
		function init_events(ele) {
		    ele.each(function () {
		        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
		        // it doesn't need to have a start or end
		        var eventObject = {
		            title: $.trim($(this).text()) // use the element's text as the event title
		        }
		        // store the Event Object in the DOM element so we can get to it later
		        $(this).data('eventObject', eventObject)
		        // make the event draggable using jQuery UI
		        $(this).draggable({
		            zIndex        : 1070,
		            revert        : true, // will cause the event to go back to its
		            revertDuration: 0  //  original position after the drag
		        })
		    })
	    }

		document.addEventListener('DOMContentLoaded', function() {
		    var Calendar = FullCalendar.Calendar;
		    var Draggable = FullCalendarInteraction.Draggable;

		    var containerEl = document.getElementById('external-events');
		    var calendarEl = document.getElementById('calendar');
		    var checkbox = document.getElementById('drop-remove');

		/*-------- initialize the external events ---------*/

	    	init_events($('#external-events div.external-event'))
		    // initialize the external events
		    new Draggable(containerEl, {
		   		itemSelector: '.external-event',
		    	eventData: function(eventEl) {
			        return {
			        	title: eventEl.innerText,
			        	backgroundColor: eventEl.style.backgroundColor,
			        };
		    	}
		    });

		    // initialize the calendar
			var calendar = new Calendar(calendarEl, {
			    plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
			    header: {
			        left: 'prev,next today',
			        center: 'title',
			        right: 'dayGridMonth,timeGridWeek,timeGridDay'
				},
			    editable: true,
			    navLinks: true,
			    locale: 'ru',
			    droppable: true, 
			    drop: function(info) {
			        if (checkbox.checked) {
			        	info.draggedEl.parentNode.removeChild(info.draggedEl);
			        }
			    },
			});
			calendar.render();
		});

    	/* ADDING EVENTS */
	    var currColor = '#3c8dbc' //Red by default
	    var colorChooser = $('#color-chooser-btn')

	    $('#color-chooser > li > a').click(function (e) {
	        e.preventDefault()
	        currColor = $(this).css('color')
	        $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
	    })

	    $('#add-new-event').click(function (e) {
	        e.preventDefault()

	        var val = $('#new-event').val()
	        if (val.length == 0) {
	        	return
	        }

	        var event = $('<div />')
	        event.css({
		        'background-color': currColor,
		        'border-color'    : currColor,
		        'color'           : '#fff'
	        }).addClass('external-event')
	        event.html(val)
	        $('#external-events').prepend(event)

	        init_events(event);

	        $('#new-event').val('')
	    });

    </script>
@endsection

