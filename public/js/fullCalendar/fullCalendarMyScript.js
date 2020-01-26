$(document).ready(function () {
    let calendarBlock = $('#eventCalendar');
    jQuery.noConflict();


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        }
    });

    $('.multiselect-ui').multiselect({
        includeSelectAllOption: true,
        enableFiltering: true,
        defaultChecked: true,
        nonSelectedText: 'Выбрать',
        selectAllText: 'Все',
        allSelectedText: 'Все'
    });

    $(document).ajaxSuccess(function () {
        $('.multiselect-ui').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            defaultChecked: true,
            nonSelectedText: 'Выбрать',
            selectAllText: 'Все',
            allSelectedText: 'Все'
        });
    });

    function anchorFullCalendar(data) {
        $('.fc-day-grid-event.fc-h-event.fc-event').click(function (e) {
            e.preventDefault();
            $('.bs-example-modal-lg').modal('show');

            for (let i in data) {
                if (data[i].id == $(this).attr('href')) {
                    $('#showAnchorFullCalendarHeader').html(`<h3 class="modal-title">Задачу поставил: ${data[i].from_users ? data[i].from_users.name : 'Не назначен'}</h3>`);
                    $('#showAnchorFullCalendarContent').html(`<div class=row>Исполнитель: ${data[i].users.name}  <br/> ${data[i].title} ${data[i].url ? ' <a href="/contact/' + data[i].url + '/edit">Перейти</a>' : ''}</div><br/>
                                                                    <div class="row"><button class="btn btn-danger eventDelete" value="${data[i].id}">Удалить</button ></div>`);
                    $('.eventDelete').click(function (e) {
                        e.preventDefault();
                        $.ajax({
                            url: `/event_delete/${$(this).val()}`,
                            method: 'delete',
                            success: function (result) {
                                if (!Array.isArray(result)) {
                                    alert(result);
                                    $('#showEventClose').click();
                                    renderCalendar();
                                }
                            }
                        });
                    });
                }
            }
        });
    };

    function renderAnchorFullCalendarMore(data) {
        $('.fc-more').click(function (e) {
            anchorFullCalendar(data);
        });
    }

    function renderAnchorFullCalendarDefault(data) {
        $('.fc-button.fc-state-default').click(function (e) {
            anchorFullCalendar(data);
        });
    }


    function showCalendar() {
        $.ajax({
            url: '/showEvents',
            method: 'post',
            // data: {
            //     user_id: $('#selectUserShowSelect').val()
            // },
            cache: false,
            success: function (result) {
                calendarBlock.fullCalendar(JSON.parse(result[0]));
                anchorFullCalendar(result[1]);
                renderAnchorFullCalendarDefault(result[1]);
                renderAnchorFullCalendarMore(result[1]);
            }
        })
    }

    showCalendar();

    function renderCalendar() {
        $.ajax({
            url: '/showEvents',
            method: 'post',
            data: {
                // user_id: $('#selectUserShowSelect').val()
                group_id: $('#leadGroupSelector').val(),
                regionManager_id: $('#leadRegionManagerSelector').val(),
                salon_id: $('#leadSalon').val(),
                user_id: $('#leadManagerSelector').val()
            },
            cache: false,
            success: function (result) {
                calendarBlock.fullCalendar('removeEvents');
                calendarBlock.fullCalendar('addEventSource', JSON.parse(result[0]).events);
                calendarBlock.fullCalendar('rerenderEvents');
                anchorFullCalendar(result[1]);
                renderAnchorFullCalendarDefault(result[1]);
                renderAnchorFullCalendarMore(result[1]);
            }
        })
    }

    $('#setEvent').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: '/events',
            method: `post`,
            data: $('#setEvent').serializeArray(),
            success: function (result) {
                if (!Array.isArray(result)) {
                    $('#addEventFullCalendarClose').click();
                    $('#setEvent').trigger('reset');
                    renderCalendar();
                    alert(result);
                } else {
                    alert(result);
                }
            }
        });
    });

    // $('#selectUserShowSelect').change(function () {
    //     renderCalendar()
    // })

    $('#organizerFilterExecute').on('click', function (e) {
        e.preventDefault();

        renderCalendar();
        // let data = {
        //     group_id: $('#leadGroupSelector').val(),
        //     regionManager_id: $('#leadRegionManagerSelector').val(),
        //     salon_id: $('#leadSalon').val(),
        //     user_id: $('#leadManagerSelector').val()
        // }
        // console.log(data)
    })

});

