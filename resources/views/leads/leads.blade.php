@extends('adminlte::page')

@section('content')

    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">

    @php
        use App\Support\UserRole\SelectRole;use App\UserRegions;
        $manager = \App\Support\Support::contenHeaderManager();
        $user = \Illuminate\Support\Facades\Auth::user();
    @endphp

    <!-- Модальное окно отмены лида -->
    @include('leads.leadModalDisabled')

    <!-- Модальное окно просмотра лидов по клиенту -->
    @include('leads.leadModalOneClient')

    <!-- Модальное окно подтвержения оплаты -->
    @include('leads.leadModalAccountPay')

    <section class="content__wrapper" data-id="living-tape">
        
        <!-- Основная информация -->
        <div class="container main-info">
            <div class="container__avatar">
                <img src="img/living-tape/avatar-main.png" alt="User avatar" class="avatar-lg">
            </div>
            <div class="container__content">
                <div class="content__item">
                    <span class="item--title">Ф.И.О</span>
                    <span class="item--subtitle">{{$manager['user']->name}}</span>
                </div>
                <div class="content__item">
                    <span class="item--title">Салон</span>
                    <span class="item--subtitle">{{isset($manager['branch']->name) ? $manager['branch']->name : 'Не установленна'}}</span>
                </div>
            </div>
        </div>

        <!-- Дополнительная информация -->
        <div class="container additonal-info">
            <div class="container__content">
                <div class="content__item">
                    <span class="item--title">Дата открытия</span>
                    <span class="item--subtitle">{{isset($manager['branch']->date_opening) ? \Carbon\Carbon::make($manager['branch']->date_opening)->format('d-m-Y') : 'Дата не указана'}}</span>
                </div>
                <div class="content__item">
                    <span class="item--title">Дата принятия на работу</span>
                    <span class="item--subtitle">{{isset($manager['user']->date_employment) ? date('d-m-Y', strtotime($manager['user']->date_employment)) : 'Дата не указана'}}</span>
                </div>
                <div class="content__item">
                    <span class="item--title">Контактный номер</span>
                    <span class="item--subtitle">{{isset($manager['branch']->phone) ? $manager['branch']->phone : 'Телефон не указан'}}</span>
                </div>
                <div class="content__item">
                    <span class="item--title">Адрес</span>
                    <span class="item--subtitle">{{isset($manager['regions']->name) ? $manager['regions']->name : 'Регион не указан'}} , {{isset($manager['branch']->address) ? $manager['branch']->address : 'Адрес не указан'}}</span>
                </div>
            </div>
        </div>

        <div class="container__wrapper">
            <!-- План для менеджера -->
            <div class="container main-plan">
                <div class="container__title">
                    <h2 class="title">План на {{is_null($manager['monthPlan']) ? __('support.'.date('F', strtotime($manager['monthPlan']->month)), [], 'ru').' '.date('Y', strtotime($manager['monthPlan']->year)) : __('support.'.$manager['date']->format('F'), [], 'ru').' '. $manager['date']->format('Y')}}</h2>
                </div>
                <div class="container__content">
                    <div class="content__item">
                        <span class="item--text">{{empty($manager['monthPlan']) ? 0 : $manager['monthPlan']->frameworks}} грн</span>
                    </div>
                    <div class="content__item">
                        <span class="item--text">{{empty($manager['monthPlan']) ? 0 : $manager['monthPlan']->sum}} шт</span>
                    </div>
                </div>
            </div>

            <!-- Факт выполнения плана -->
            <div class="container chart-pie" data-id="done">
                <div class="container__title">
                    <h3 class="title">Факт выполнения плана</h3>
                </div>
                <div class="container__content">
                    <div class="content__pie">
                        <div class="pie pie__lvl-1">{{$manager['frameworkPercent']}}%</div>
                        <div class="pie pie__lvl-2">{{$manager['sumPercent']}}%</div>
                        <div class="pie__lvl-info">
                            <div class="info__top">
                                <span class="info--value">{{$manager['framework_payments']}} шт</span>
                                <span class="info--percent blue">{{$manager['frameworkPercent']}} %</span>
                            </div>
                            <div class="info__bot">
                                <span class="info--value">{{$manager['sum_payments']}} грн</span>
                                <span class="info--percent dark-blue">{{$manager['sumPercent']}} %</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Прогноз выполнения -->
            <div class="container chart-pie" data-id="prognos">
                <div class="container__title">
                    <h3 class="title">Факт выполнения плана</h3>
                </div>
                <div class="container__content">
                    <div class="content__pie">
                        <div class="pie pie__lvl-1">{{$manager['frameworkPercent']}}%</div>
                        <div class="pie pie__lvl-2">{{$manager['sumPercent']}}%</div>
                        <div class="pie__lvl-info">
                            <div class="info__top">
                                <span class="info--value">{{$manager['framework_payments']}} шт</span>
                                <span class="info--percent blue">{{$manager['frameworkPercent']}} %</span>
                            </div>
                            <div class="info__bot">
                                <span class="info--value">{{$manager['sum_payments']}} грн</span>
                                <span class="info--percent dark-blue">{{$manager['sumPercent']}} %</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <!-- Визуальное представление живой ленты -->
        <div class="container visual-chart">
            <div class="container__content">
                <div class="content__filter-list" id="funnel" data-status='[]'>

                    <div class="list__item all" id="all">
                        <div class="item__btn" data-id="5,11,12,13,14,15">
                            <span class="btn--text">Все</span>
                        </div>
                        <div class="item__val">
                            <span class="val--text">0</span>
                        </div>
                    </div>

                    <div class="list__item new" id="new">
                        <div class="item__btn" data-id="5">
                            <span class="btn--text">Новый</span>
                        </div>
                        <div class="item__val">
                            <span class="val--text">0</span>
                        </div>
                    </div>

                    <div class="list__item processing" id="processing">
                        <div class="item__btn" data-id="11">
                            <span class="btn--text">Обработка</span>
                        </div>
                        <div class="item__val">
                            <span class="val--text">0</span>
                        </div>
                    </div>

                    <div class="list__item measurement" id="measurement">
                        <div class="item__btn" data-id="12">
                            <span class="btn--text">Замер</span>
                        </div>
                        <div class="item__val">
                            <span class="val--text">0</span>
                        </div>
                    </div>

                    <div class="list__item offer" id="offer">
                        <div class="item__btn" data-id="13">
                            <span class="btn--text">Предложение</span>
                        </div>
                        <div class="item__val">
                            <span class="val--text">0</span>
                        </div>
                    </div>

                    <div class="list__item waiting" id="waiting">
                        <div class="item__btn" data-id="14">
                            <span class="btn--text">Выставлен счёт</span>
                        </div>
                        <div class="item__val">
                            <span class="val--text">0</span>
                        </div>
                    </div>

                    <div class="list__item complete" id="complete">
                        <div class="item__btn" data-id="15">
                            <span class="btn--text">Оплачен</span>
                        </div>
                        <div class="item__val">
                            <span class="val--text">0</span>
                        </div>
                    </div>

                </div>
                <div class="content__visual-chart">
                    <img src="img/living-tape/living-tape.svg" alt="" class="visual-chart">
                </div>
            </div>
        </div>

        <div class="container table">
            <div class="container__filters">

                <!-- Конпка на создание лида для менеджера -->
                @if(\Illuminate\Support\Facades\Auth::user()->role_id == 3 || \Illuminate\Support\Facades\Auth::user()->role_id == 5)
                <a href="{{route('storeLead')}}" class="btn btn--crm">
                    <span class="btn--title">Создать новый Лид</span>
                </a>
                @endif

                <!-- Форма для создания эксель документа (СКРЫТО) -->
                @if(\Illuminate\Support\Facades\Auth::user()->role_id == 1 )
                    <div class="xls">
                        <form method="post" action="{{route('createLeadXls')}}" id="leadFormXls">
                            {{csrf_field()}}
                            <input type="hidden" name="leadDateFrom" id="leadDateFromXls">
                            <input type="hidden" name="leadDateTo" id="leadDateToXLS">

                            <input type="hidden" name="group_id" id="leadGroupSelectorXls">
                            <input type="hidden" name="regionManager_id" id="leadRegionManagerSelectorXls">
                            <input type="hidden" name="salon_id" id="leadSalonXls">
                            <input type="hidden" name="user_id" id="leadManagerSelectorXls">

                            <input type="hidden" name="leadStatusId" id="leadStatusXls">
                            
                            <button type="submit" class="btn btn--crm">
                                <span class="btn--title">xls</span>
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Основные фильтры для таблиц -->
                @include('leads.filter.leadFilter')
                
            </div>
            <div class="container__table">

                 <!-- Основные таблица -->
                <table class="table {{\Illuminate\Support\Facades\Auth::user()->role_id == 3 ? 'table-manager': ''}}"  id="leads">
                    <thead class="table__head">
                        <tr class="head__row">

                            <th class="row__title">Лид</th>
                            <th class="row__title">Дата</th>
                            <th class="row__title">Регион</th>
                            <th class="row__title">Имя</th>
                            <th class="row__title">Телефон</th>
                            <th class="row__title">Статус</th>
                            <th class="row__title">Мененджер</th>
                            <th class="row__title">Комментарий</th>

                            <!-- Для гл.рук -->
                            @if(\Illuminate\Support\Facades\Auth::user()->role_id == 5)
                            <th class="row__title">Мененджер</th>
                            @endif
                            
                            <!-- Для менеджера-->
                            @if(\Illuminate\Support\Facades\Auth::user()->manager)
                            <th class="row__title">Дейст.</th>
                            <th class="row__title">Отказ</th>
                            @endif

                        </tr>
                    </thead>
                    <tbody class="table__body"></tbody>
                </table>
            </div>
        </div>
    </section>
    <input id="userRoleId" type="hidden" value="{{\Illuminate\Support\Facades\Auth::user()->role_id}}">
    <input id="userManager" type="hidden" value="{{\Illuminate\Support\Facades\Auth::user()->manager}}">
@stop

@section('tmp_js')
    <script src="{{asset('js/users/userIndex.js')}}"></script>
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('js/lead/leadFilter.js')}}"></script>
    <script src="{{asset('js/components/pie.js')}}"></script>
    <script src="{{asset('js/components/progressBar.js')}}"></script>
    <script src="{{asset('js/components/living-tape/ClientLead.js')}}"></script>
    <script src="{{asset('js/components/living-tape/UpdaterLead.js')}}"></script>
    <script src="{{asset('js/components/living-tape/Funnel.js')}}"></script>
    <script src="{{asset('js/components/living-tape/Table.js')}}"></script>
    <script src="{{asset('js/components/XlsData.js')}}"></script>
    
    <script>
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        $(document).ready(()=>{
            // Инициализация компонентов
            let table = new Table()
            let funnel = new Funnel();
           

            // Отрисовка данных
            let leadTable = table.render();
                            funnel.render();


            // Обработка событий на странице
            // Поиск номера телефона в таблице
            $('#searchPhone').on('keyup', function(){
                leadTable.columns(4).search(this.value).draw();
            });

            leadTable.on('draw', function () {
                progressBar();
            });


            // Применение фильтров Даты, Группы, Региона, Менеджера, Салона
            $('body').on('change', '#leadDateFrom, #leadDateTo, #sectorGroupFilter, #sectorRegionManagerFilter, #sectorSalonFilter, #sectorManagerFilter', function(){
                funnel.reload();
                leadTable.ajax.reload();
            });
            

            // Фильтрация таблицы по статусу из воронки
            $('body').on('click', '#funnel .item__btn', function(){
                $('#funnel').attr('data-status', `[${ $(this).attr('data-id')}]`);
                leadTable.ajax.reload();
            });


            // Генерирование XLS документа
            $('body').on('submit', '#leadFormXls', function(){
                leadXlsFilter();
            });

            // Просмотр лидов по клиенту
             $('body').on('click', '.numberOfLeadsPerClient', function () {
                $('#leadOneClientModal').modal('toggle');
                let clientLeadRows = new ClientLead($(this).val());
                clientLeadRows.render();
            });


            // Обновление статуса и комментария клиента
            $('body').on('click', '.update-lead-form', function(e){
                e.preventDefault();

                // Формируем данные
                let data = $(this).serializeArray();

                // Инициализируем компонент
                let lead = new UpdaterLead(data, leadTable.ajax.reload);

                // Выполняем обновление
                lead.update();
            });


            // Удаление лида
            $('body').on('click', '.disabledFormLead', function(e){
                e.preventDefault();

                // Формируем данные
                let data = $(this).serializeArray();

                // Инициализируем компонент
                let lead = new UpdaterLead(data, leadTable.ajax.reload);

                 // Выполняем удаление
                lead.delete();
            });
        });
    </script>


@endsection

