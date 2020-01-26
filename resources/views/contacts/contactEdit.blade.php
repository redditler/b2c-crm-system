@extends('adminlte::page')

@section('content')
    <input type="hidden" name="contactFormEdit" value="1">
    <input type="hidden" id="userName" value="{{Auth::user()->name}}">

    <section class="content__wrapper" data-id="edit-contact">
        @include('contacts.changeName')
        <div class="container main-info">
            <div class="container__main-edit">
                <div class="container__status-avatar" id="stausAvatar">
                    <div class="avatar__block">
                        <img src="/img/sidebar/avatar-default-min.png" alt="avatar" class="avatar">
                    </div>
                </div>
                <div class="container__status-fio">
                    <div class="container__fio-editor">
                        <span>
                            <span class="container__full-name">{{$contact->fio}}</span>
                            <a data-toggle="modal" data-target=".bs-changeName-modal-lg" class="fa fa-pencil"></a>
                        </span>
                    </div>
                    <div class="container__status-editor">
                        <span class="status-name" id="statusName"></span>
                    </div>
                </div>
            </div>
            <div class="container__event-btn">
                @include('contacts.addEvent')
            </div>
        </div>
        <div class="container user-info">
            <div class="container__user-edit">
                <div class="edit__element-list">

                    {{-- Дата регистрации клиента --}}
                    <div class="list__item edit__element create-at">
                        <div class="item__title">
                            <span class="title">Зарегис-ан</span>
                        </div>
                        <div class="item__content">
                            <span class="content__description">{{\Carbon\Carbon::make($contact->created_at)->format('d / m / Y')}}</span>
                        </div>
                    </div>

                    {{-- Пол клиента --}}
                    <div class="list__item edit__element gender">
                        <div class="item__title">
                            <span class="title">Пол</span>
                        </div>
                        <div class="item__content">
                            <div class="content__select">
                                <select class="form-control" name="gender" id="gender">
                                    @if(is_null($contact->gender))
                                        <option disabled selected>Укажите</option>
                                        @foreach($genders as $key => $gender)
                                            <option value="{{$key}}">{{$gender}}</option>
                                        @endforeach
                                    @else
                                        <option value="{{$contact->gender}}" selected>{{$genders[$contact->gender]}}</option>
                                        @foreach($genders as $key => $gender)
                                            @if($contact->gender != $key)
                                                <option value="{{$key}}">{{$gender}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <i class="fa fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Возраст клиента --}}
                    <div class="list__item edit__element age">
                        <div class="item__title">
                            <span class="title">Возраст</span>
                        </div>
                        <div class="item__content">
                            <div class="content__input">
                                <input type="text" name="age" id="age" class="form-control" value="{{$contact->age}}">
                            </div>
                        </div>
                    </div>

                    {{-- Основной номер --}}
                    <div class="list__item edit__element main-phone phones">
                        <div class="item__title">
                            <span class="title"><i class="fa fa-phone" aria-hidden="true"></i> основ.</span>
                        </div>
                        <div class="item__content">
                            <div class="content__input">
                                <input id="mianContactPhone" type="text" class="form-control">
                            </div>
                            <div class="content__description select-messenger">
                                <div class="select-icon" data-id="sms"></div>
                            </div>
                            <div class="content__select">
                                <select class="form-control" id="mainPhoneMessenger">
                                    <option value="sms">SMS</option>
                                    <option value="viber">Viber</option>
                                    <option value="telegram">Telegram</option>
                                    <option value="whatsapp">WhatsApp</option>
                                </select>
                                <i class="fa fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Доп.номер --}}
                    <div class="list__item edit__element additional-phone phones">
                        <div class="item__title">
                            <span class="title"><i class="fa fa-phone" aria-hidden="true"></i> допол.</span>
                        </div>
                        <div class="item__content">
                            <div class="content__input">
                                <input id="addContactPhone" type="text" class="form-control">
                            </div>
                            <div class="content__description select-messenger">
                                <div class="select-icon" data-id="sms"></div>
                            </div>
                            <div class="content__select">
                                <select class="form-control" id="addPhoneMessenger">
                                    <option value="sms">SMS</option>
                                    <option value="viber">Viber</option>
                                    <option value="telegram">Telegram</option>
                                    <option value="whatsapp">WhatsApp</option>
                                </select>
                                <i class="fa fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <form class="edit__element-list" id="contactFormEdit">

                    {{-- Город --}}
                    <div class="list__item edit__element city">
                        <div class="item__title">
                            <span class="title">Город</span>
                        </div>
                        <div class="item__content">
                            <div class="content__select">
                                <select class="form-control" id="region_id" name="region_id" required>
                                    @if(is_null($contact->region_id))
                                        <option disabled selected>Выберите регион</option>
                                    @elseif($contact->region_id)
                                        <option value="{{$contact->region_id}}" selected>{{$contact->regions->name}}</option>
                                    @endif
                                    @foreach(\App\Regions::getUserRegions() as $city)
                                        <option value="{{$city['id']}}">{{$city['name']}}</option>
                                    @endforeach
                                </select>
                                <i class="fa fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Адрес --}}
                    <div class="list__item edit__element addres">
                        <div class="item__title">
                            <span class="title">Адрес</span>
                        </div>
                        <div class="item__content">
                            <div class="content__input">
                                <input type="text" name="city" id="city" class="form-control" placeholder="Адрес" value="{{$contact->city}}">
                            </div>
                        </div>
                    </div>

                    {{-- e-mail --}}
                    <div class="list__item edit__element mail">
                        <div class="item__title">
                            <span class="title">e-mail</span>
                        </div>
                        <div class="item__content">
                            <div class="content__input">
                                <input type="email" name="email" id="email" class="form-control" value="{{$contact->email}}">
                            </div>
                        </div>
                    </div>

                    {{-- Источ. клиента --}}
                    <div class="list__item edit__element source">
                        <div class="item__title">
                            <span class="title">Источ. клиента</span>
                        </div>
                        <div class="item__content">
                            <div class="content__select">
                                <select class="form-control thir-elem" name="sources_id" id="sources_id" required>
                                    @if(!$contact->sources_id)
                                        <option selected disabled>Источник не указан</option>
                                        @foreach(\App\CustomerSource::query()->get() as $val)
                                            <option value="{{$val->id}}" title="{{$val->description}}">{{$val->name}}</option>
                                        @endforeach
                                    @else
                                        <option selected value="{{$contact->sources_id}}">{{$contact->contactSources->name}}</option>
                                        @foreach(\App\CustomerSource::query()->get() as $val)
                                            <option value="{{$val->id}}" title="{{$val->description}}">{{$val->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <i class="fa fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Квалиф-ция --}}
                    <div class="list__item edit__element quality">
                        <div class="item__title">
                            <span class="title">Квалиф-ция</span>
                        </div>
                        <div class="item__content">
                            <div class="content__select">
                                <select class="form-control" name="contact_quality_id" id="contact_quality_id" required>
                                    @if($contact->contact_quality_id)
                                        <option selected value="{{$contact->contact_quality_id}}">{{$contact->contactQuality->title}}</option>
                                    @else
                                        <option selected disabled>Выбрать</option>
                                    @endif
                                    @foreach(\App\ContactQuality::all() as $value)
                                        <option value="{{$value->id}}">{{$value->title}}</option>
                                    @endforeach
                                </select>
                                <i class="fa fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Ценовой сегмент --}}
                    <div class="list__item edit__element price-segment">
                        <div class="item__title">
                            <span class="title">Ценовой сегмент</span>
                        </div>
                        <div class="item__content">
                            <div class="content__select">
                                <select class="form-control" name="price_category_id" id="price_category_id" required>
                                    @if($contact->contactPriceCategory)
                                        <option selected value="{{$contact->contactPriceCategory->id}}">{{$contact->contactPriceCategory->name}}</option>
                                    @else
                                        <option selected disabled>Выбрать</option>
                                    @endif
                                    @foreach(\App\ContactPriceCategory::all() as $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                                <i class="fa fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    
                    <button id="sendMainClientInfo" type="button" class="btn btn--default blue" >Изменить</button>
                </form>
            </div>
            <div class="container__user-comment" data-show="comment">
                <div class="comment-area__header">
                    <div class="header__content">
                        <button class="btn btn--default" id="commentControl" disabled>Комментарии</button>

                        @if(\Illuminate\Support\Facades\Auth::user()->role_id == 1)
                        <button class="btn btn--default" id="historyControl">История</button>
                        @endif
                        
                    </div>
                </div>
                <div class="comment-area__body">
                    <div class="body__content">
                        <div class="content__area" id="messagesArea"></div>
                    </div>
                </div>
                <div class="history-area__body">
                    <div class="body__content">
                        <div class="content__area" id="historyArea" style="overflow-y: auto;"></div>
                    </div>
                </div>
                <div class="comment-area__footer">
                    <div class="footer__content">
                        <form class="message__input">
                            <textarea type="text" class="form-control" id="commentInput" placeholder="Введите текст"></textarea>
                            <button type="button" class="btn btn--default blue" id="addCommentButton">Добавить</button>
                        </form>
                    </div>
                </div>
            </div>
            @if(\Illuminate\Support\Facades\Auth::user()->role_id != 3 && \Illuminate\Support\Facades\Auth::user()->role_id == 1)
            <div class="container__admin-edit">
                <form class="edit__element-list">

                    {{-- Ответственный менеджер --}}
                    <div class="list__item edit__element manager_id">
                        <div class="item__title">
                            <span class="title">Ответственный менеджер</span>
                        </div>
                        <div class="item__content">
                            <div class="content__select">
                                    <select class="form-control" id="manager_id" name="user_id" required>
                                        @if(!$contact->user_id)
                                            <option disabled selected>Выберите менеджера</option>
                                        @endif

                                        @foreach(\App\User::userManager() as $value)
                                            @if($contact->user_id == $value->id)
                                                <option value="{{$value->id}}" selected>{{$value->name}}</option>
                                            @endif
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                    <i class="fa fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Группа --}}
                    <div class="list__item edit__element group_id">
                        <div class="item__title">
                            <span class="title">Группа</span>
                        </div>
                        <div class="item__content">
                            <div class="content__select">
                                    <select class="form-control" id="group_id" name="group_id" required>
                                        @if(is_null($contact->group_id))
                                            <option disabled selected>Выберите групу</option>
                                        @else
                                            <option value="{{$contact->group_id}}" selected>{{$contact->group->name}}</option>
                                        @endif
            
                                        @if(\Illuminate\Support\Facades\Auth::user()->role_id == 1)
                                            @foreach(\App\UserGroups::query()->where('slug', '!=', 'all')->get() as $group)
                                                <option value="{{$group->id}}">{{$group->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <i class="fa fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    <button id="sendAdminClientInfo" type="button" class="btn btn--default blue">Изменить</button>
                </form>
            </div>
            @endif
        </div>
    </section>

@endsection

@section('tmp_js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="{{asset('js/users/userIndex.js')}}"></script>
    <script src="{{asset('js/components/colorpicker.js')}}"></script>
    <script src="{{asset('js/components/PhoneEditor.js')}}"></script>
    <script src="{{asset('js/components/UserEditor.js')}}"></script>
    <script src="{{asset('js/components/CommentArea.js')}}"></script>
    <script src="{{asset('js/components/HistoryArea.js')}}"></script>
    <script src="{{asset('js/components/Comment.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        $(document).ready(()=>{
            // Получение данных
            let contact = JSON.parse(`{!! $contact !!}`);

            // Инициализация копонетов
            let contactEditor = new UserEditor(contact);
            let contactCommentArea = new CommentArea(contact);
            let contactHistoryArea = new HistoryArea(contact);

            // Отрисовка данных
            contactEditor.render();
            contactCommentArea.render();
            contactHistoryArea.render();
            colorpicker();
            

            // Обработка событий на странице
            // Добавить комментраий по клиенту
            $('#addCommentButton').on('click', ()=>{
                contactCommentArea.addComment();
            });

            // Изменить информацию о клиенте(Менеджер)
            $('#sendMainClientInfo').on('click', ()=>{
                contactEditor.update();
            })

            // Изменить имя клиента
            $('#changeName').on('click', ()=>{
                contactEditor.updateName();
            })
            
            // Изменить информацию о клиенте(Администратор)
            $('#sendAdminClientInfo').on('click', ()=>{
                contactEditor.updateAdmin();
            })

            // Показать/Cкрыть комментарии или историю
            $('#commentControl').on('click', ()=>{
                $('.container__user-comment').attr('data-show', 'comment');
                $('#commentControl').attr('disabled', true);
                $('#historyControl').attr('disabled', false);
            });

            // Показать/Cкрыть комментарии или историю
            $('#historyControl').on('click', ()=>{
                $('.container__user-comment').attr('data-show', 'history');
                $('#historyControl').attr('disabled', true);
                $('#commentControl').attr('disabled', false);
            });

            
        });
    </script>
@endsection