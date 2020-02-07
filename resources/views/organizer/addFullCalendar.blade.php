<div class="row" id="eventModal">
        <div class="col-md-12">
            <button class="btn btn--crm" data-toggle="modal" data-target=".bs-addEvent-modal-lg">
                <span class="btn--title">Добавить задачу</span>
            </button>
    
            <div class="modal fade bs-addEvent-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg style-for-modal-event">
                    <div class="modal-header">
                        <h3 class="modal__title">Добавить задачу</h3>
                    </div>
                    <div class="modal-body">
                        <form id="setEvent">
                            {{csrf_field()}}
    
                            <label for="eventTitle" class="event-for">
                                <span class="title">О чём напомнить:</span>
                                <input type="text" class="form-control title-event" id="eventTitle" name="title" required>
                            </label>
    
                            <div class="event-date">
                                <span class="date-event-change">Когда напомнить</span>
    
                                <label for="eventStartDate" class="event-from">
                                    <span class="subtitle">c</span>
                                    <input type="text" class="form-control datepicker" id="eventStartDate" name="start_date" required>
                                </label>
    
                                <label for="eventEndDate" class="event-to">
                                    <span class="subtitle">по</span>
                                    <input type="text" class="form-control datepicker" id="eventEndDate" name="end_date" required>
                                </label>
                            </div>
    
                            @if(!(\Illuminate\Support\Facades\Auth::user()->role_id == 3 || \Illuminate\Support\Facades\Auth::user()->role_id == 5))
                                <label for="client_user_id[]" class="event-manager">
                                    <span class="title">Сотрудник</span>
                                    <select name="client_user_id[]" id="selectAddUser" class="multiselect-ui form-control " multiple="multiple">
                                        @foreach(\App\User::getEventUserTree(\Illuminate\Support\Facades\Auth::user()) as $value)
                                            <option value="{{$value->id}}" >{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </label>
                            @endif
    
                            <label for="color_event" class="event-color">
                                <span class="title">Цвет заметки</span>
                                <input type="hidden" class="form-control" id="color_event" name="color_event" value="#01b6cf">
                                <div class="colorpicker"></div>
                            </label>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--default" id="closeEventModal" data-dismiss="modal">Закрыть</button>
                        <button class="btn btn--default blue" id="sendEventBtn">Создать заметку</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    