@extends('adminlte::page')

@section('content')
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    
    <section class="content__wrapper" data-id="contact-list">
        <div class="container filters_list">

            <div class="list__item filter__date">
                <form class="filter__wrapper">
                    <span class="filter__date-title">с</span>
                    <input type="text" class="date__input datepicker" id="ContactDateFrom" value="{{\App\Leed::dateFromLead()[0]}}">
                    
                    <span class="filter__date-title">по</span>
                    <input type="text" class="date__input datepicker"  id="ContactDateTo" value="{{\App\Leed::dateFromLead()[1]}}">
                </form>
            </div>

            <div class="list__item filter__quality">
                <label class="filter__wrapper">
                    <select name="contact_quality_id[]" id="qualityId" class="select multiselect-ui" multiple="multiple">
                        @foreach(\App\ContactQuality::getListQuality() as $quality)
                            <option value="{{$quality['id']}}">{{$quality['title']}}</option>
                        @endforeach
                        <option value="0">Не выбран</option>
                    </select>
                </label>
            </div>

            <div class="list__item filter__region">
                <label class="filter__wrapper">
                    <select name="client_region_id[]" id="clientRegionId" class="select multiselect-ui" multiple="multiple">
                        @foreach(\App\Regions::getUserRegions() as $region)
                            <option value="{{$region['id']}}">{{$region['name']}}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="list__item filter__manager">
                <label class="filter__wrapper">
                    <select name="client_user_id[]" id="clientUserId" class="select multiselect-ui" multiple="multiple">
                        @if(\Illuminate\Support\Facades\Auth::user()->role_id == 4 )
                            @foreach(\App\UserRm::getRmManagers()->get() as $manager)
                                <option value="{{$manager->id}}">{{$manager->name}}</option>
                            @endforeach
                        @else
                            @foreach(\App\User::UserManager() as $manager)
                                <option value="{{$manager->id}}">{{$manager->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </label>
            </div>
            
        </div>
    
        <div class="container eptic-content"></div>
        @if(\Illuminate\Support\Facades\Auth::user()->role_id == 1 )
            <div>
                <form method="post" action="{{route('exportXls')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="ContactDateFromXls" id="ContactDateFromXls">
                    <input type="hidden" name="ContactDateToXLS" id="ContactDateToXls">
                    <input type="hidden" name="qualityId[]" id="qualityIdXls">
                    <input type="hidden" name="clientRegionIdXls[]" id="clientRegionIdXls">
                    <input type="hidden" name="client_user_idXls[]" id="client_user_idXls">
                    <input type="hidden" name="contactPhoneXls" id="contactPhoneXls">

                    {{-- <input type="submit" class="btn btn-primary" value="xls"> --}}
                </form>
            </div>
        @endif
        <!-- Данные таблицы -->

        <div class="container card-list">
            <div class="filter filter__phone" style="width: 170px;margin-bottom: 30px;">
                <label class="filter__content">
                        <input type="search" placeholder="номер телефона" aria-controls="leads" class="filter__input phone_search">
                        <div class="filter__icon">
                            <i class="icon fa fa-search" aria-hidden="true"></i>
                        </div>
                </label>
            </div>
            <table id="indexContactTable" style="display: none;"></table>
        </div>
    </section>

@endsection


@section('tmp_js')
    <script src="{{asset('js/components/contactList.js')}}"></script>
    <script src="{{asset('js/components/contactCard.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#ContactDateFrom').val($('#ContactDateFrom').val().split(' ')[0]);
            $('#ContactDateTo').val($('#ContactDateTo').val().split(' ')[0]);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('#_token').val()
                }
            });

            $('.filter__quality .multiselect-ui').multiselect({nonSelectedText: 'Квалификация',includeSelectAllOption: true,enableFiltering: true, defaultChecked: true,selectAllText: 'Все',allSelectedText: 'Все'});
            $('.filter__region .multiselect-ui').multiselect({nonSelectedText: 'Регион', includeSelectAllOption: true,enableFiltering: true,defaultChecked: true,selectAllText: 'Все',allSelectedText: 'Все'});
            $('.filter__manager .multiselect-ui').multiselect({nonSelectedText: 'Менеджер',includeSelectAllOption: true,enableFiltering: true,defaultChecked: true,selectAllText: 'Все',allSelectedText: 'Все'});

            let contactTable = $('#indexContactTable').DataTable({
                order: [['0', "desc"]],
                processing: true,
                serverSide: true,
                "lengthMenu": [[12, 24, 48, -1], [12, 24, 48, "Все"]],
                ajax: {
                    "url": '{!! route('indexShow')!!}',
                    "method": "POST",
                    'data': function (d) {
                        d.regions = $('#clientRegionId').val();
                        d.phone = $('#contactPhone').val();
                        d.userId = $('#clientUserId').val();
                        d.qualityId = $('#qualityId').val();
                        d.ContactDateFrom = $('#ContactDateFrom').val();
                        d.ContactDateTo = $('#ContactDateTo').val();
                    }
                },
                columns: [
                    {data: 'data', name: 'data'},
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'group', name: 'group'},
                    {data: 'region', name: 'region'},
                    {data: 'user', name: 'user'},
                    {data: 'contact_quality', name: 'contact_quality'},
                    {data: 'status', name: 'status'},
                    {
                        data: 'id', render: function (data) {
                            return `<button  class="contactInfoList" data-target=".bs-example-modal-lg" value="${data}">Открыть</button >`;
                        }
                    },
                ],
                fixedHeader: {
                    header: false
                },
                "drawCallback": function (settings) {
                    $('.contactInfoList').on('click', function () {
                        window.open(` contact/${$(this).val()}/edit`);
                    });
                },
                language: {
                    "processing": "Подождите...",
                    "search": "",
                    "lengthMenu": "Показать по _MENU_ записей",
                    "info": "_TOTAL_ записей",
                    "infoEmpty": "Записи с 0 до 0 из 0 записей",
                    "infoFiltered": "(отфильтровано из _MAX_ записей)",
                    "infoPostFix": "",
                    "loadingRecords": "Загрузка записей...",
                    "zeroRecords": "Записи отсутствуют.",
                    "emptyTable": "В таблице отсутствуют данные",
                    "paginate": {
                        "first": "Первая",
                        "previous": "<",
                        "next": ">",
                        "last": "Последняя"
                    },
                    "aria": {
                        "sortAscending": ": активировать для сортировки столбца по возрастанию",
                        "sortDescending": ": активировать для сортировки столбца по убыванию"
                    }
                },
            });

            $('.phone_search').on('keyup', function () {
                contactTable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });

            $('#addContactSubmit').click(function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'post',
                    url: `{{route('contact.store')}}`,
                    data: $('#addContact').serializeArray(),
                    success: function (result) {

                        $('#addContactResult').html('');
                        if (!Array.isArray(result)) {
                            contactTable.ajax.reload();
                            $('#addContactResult').css('color', 'green');
                            $('#addContactResult').html(result);
                            setTimeout(function () {
                                $('#btnModalContactClose').click();
                                $('#addContactResult').html('');
                                $('#addContact').trigger('reset');
                            }, 1000);
                        } else {
                            $('#addContactResult').css('color', 'red');
                            $('#addContactResult').html(result);
                        }

                    }
                });
            });

            (function xlsValue(){
                $('#ContactDateFromXls').val($('#ContactDateFrom').val());
                $('#ContactDateToXls').val($('#ContactDateTo').val());
                $('#qualityIdXls').val($('#qualityId').val());
                $('#clientRegionIdXls').val($('#clientRegionId').val());
                $('#client_user_idXls').val($('#client_user_id').val());
                $('#contactPhoneXls').val($('#contactPhone').val());
            })()

            $('#clientRegionId').change(function () {
                changeDataFilterManagers();
                contactTable.ajax.reload();
                xlsValue();
            });
            $('#contactPhone, #clientUserId, #ContactDateFrom, #ContactDateTo, #qualityId').change(function () {
                contactTable.ajax.reload();
                xlsValue();
            });
           
            function changeDataFilterManagers() {
                $.ajax({
                    type: 'post',
                    url: `{{route('renderMultiselect')}}`,
                    data: {'groupId': [2], 'regionsId': $('#clientRegionId').val()},
                    success: function (response) {
                        let data = '';
                        response.forEach(function (item) {
                            data += '<option value="' + item.id + '">' + item.name + '</option>';
                        });
                        $('#clientUserId').html(data);
                        $('#clientUserId.multiselect-ui').multiselect('rebuild');
                    }
                });
            }

            contactTable.on('draw', function () {
                $("#cardList").remove();
                $('#indexContactTable_wrapper #indexContactTable_length').after(function () {
                    return '<div id="cardList"></div>';
                });

                (function createList() {
                    let currentList = $('#indexContactTable tbody tr');
                    let newClientList = [];
                    let selector = "#indexContactTable tbody tr:nth-child";

                    for (let i = 0; i < currentList.length; i++) {
                        newClientList[i] = {
                            id: $(`${selector}(${i + 1}) td:nth-child(2)`).text(),
                            date: $(`${selector}(${i + 1}) td:nth-child(1)`).text(),
                            region: $(`${selector}(${i + 1}) td:nth-child(6)`).text(),
                            name: $(`${selector}(${i + 1}) td:nth-child(3)`).text(),
                            phone: $(`${selector}(${i + 1}) td:nth-child(4)`).text(),
                            statusName: $(`${selector}(${i + 1}) td:nth-child(9)`).text(),
                            statusId: $(`${selector}(${i + 1}) td:nth-child(9)`).text(),
                            quality: $(`${selector}(${i + 1}) td:nth-child(8)`).text()
                        };
                    }
                    let list = new contactList(newClientList);
                    list.createList('#cardList');
                    (()=>{
                        let arr = $('.progress_user');
                        for(let i = 0; i < arr.length; i++){
                            let el = $(arr[i]).data('user');
                            $(`.progress_user[data-user=${el}]`).css('width', $(`.progress_user[data-user=${el}]`).text());
                        }
                    })()
                })()

                
            });
        });

    </script>
@endsection