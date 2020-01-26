<div class="row">
    <div class="col-md-12">
        <button class="btn btn-primary" data-toggle="modal" data-target=".bs-addEvent-modal-lg">Создать задачу</button>
        <div class="modal fade bs-addEvent-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg style-for-modal-event">
                <div class="modal-content event-modal-content">
                    <button type="button" class="close" id="addEventFullCalendarClose" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <div class="form-for-add-event">
                        <form id="setEvent">
                            {{csrf_field()}}
                            <label for="eventTitle"><span class="body-event-">О чём напомнить</span>
                                <input type="text" class="form-control  title-event" id="eventTitle" name="title"
                                       required>
                            </label>
                            <div class="date-for-event-add">
                                <span class="date-event-change">Когда напомнить</span>
                                <label for="eventStartDate">
                                    <span>с</span>
                                    <input type="date" class="form-control" id="eventStartDate" name="start_date"
                                           required>
                                </label>
                                <label for="eventEndDate">
                                    <span>по</span>
                                    <input type="date" class="form-control" id="eventEndDate" name="end_date" required>
                                </label>
                            </div>
                            @if(!(\Illuminate\Support\Facades\Auth::user()->role_id == 3 || \Illuminate\Support\Facades\Auth::user()->role_id == 5))
                            <div class="select-manager-event">
                                <div class="name-select-area">
                                    <span>Сотрудник</span>
                                </div>
                                <div class="body-event-">
                                        <select name="client_user_id[]" id="selectAddUser" class="multiselect-ui form-control form-control-sm" multiple="multiple">
                                        @foreach(\App\User::getEventUserTree(\Illuminate\Support\Facades\Auth::user()) as $value)
                                            <option value="{{$value->id}}" >{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            <div class="color-event-and-button">
                                <div class="color-left-area">
                                    <label for="color_event" style="display: none">Цвет заметки
                                        <input type="color" class="form-control" id="color_event" name="color_event"
                                               value="#01b6cf">
                                    </label>
                                    <div class="select-color">
                                        <span>Цвет заметки</span>
                                        <div class="current-color-event-area">
                                            <div class="current-color-event"></div>
                                        </div>
                                    </div>

                                    <div class="color-list-for-event">

                                        <div class="color-event-1"></div>
                                        <div class="color-event-2"></div>
                                        <div class="color-event-3"></div>

                                        <div class="color-event-4"></div>
                                        <div class="color-event-5"></div>
                                        <div class="color-event-6"></div>

                                        <div class="color-event-7"></div>
                                        <div class="color-event-8"></div>
                                        <div class="color-event-9"></div>

                                    </div>
                                </div>
                                <div class="button-right-area">
                                    <input type="submit" class="btn btn-primary" value="Создать заметку">
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<br/>