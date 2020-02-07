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
                    $('#showAnchorFullCalendarHeader').html(`
                        <h3 class="modal-title">
                            <span class="user-title">Задачу поставил: </span> 
                            <span class="user-secription">${data[i].from_users ? data[i].from_users.name : 'Не назначен'}</span>
                        </h3>`);

                    $('#showAnchorFullCalendarContent').html(`
                        <div class=content_wrapper>
                            <span class="manager-title">Исполнитель:</span><span class="manager-subtitle"> ${data[i].users.name}</span>
                            <p class="event-title">${data[i].title}</p>
                            ${data[i].url ? ' <a href="/contact/' + data[i].url + '/edit">Перейти</a>' : ''}
                        </div>`);

                    $('#showEventControl').html(`
                        <button class="btn btn--default blue eventDelete" value="${data[i].id}">Удалить</button > 
                        <button type="button" class="btn btn--default" data-dismiss="modal" id="showEventClose">Закрыть</button>`
                    );

                    $('.eventDelete').click(function (e) {
                        e.preventDefault();
                        $.ajax({
                            url: `/event_delete/${$(this).val()}`,
                            method: 'delete',
                            success: function (result) {
                                if (!Array.isArray(result)) {
                                    let note = new Notes({
                                        status: 'success',
                                        content: 'Заметка удалена!',
                                        timer: '2000'
                                    }).create();
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

    $('#sendEventBtn').on('click', function () {
        $.ajax({
            url: '/events',
            method: `post`,
            data: $('#setEvent').serializeArray(),
            success: function (result) {
                if (!Array.isArray(result)) {
                    let note = new Notes({
                        status: 'success',
                        content: 'Заметка добавленна',
                        timer: '2000'
                    }).create();
                    renderCalendar();
                    $('#closeEventModal').click();
                } else {
                    let note = new Notes({
                        status: 'success',
                        content: result,
                        timer: '2000'
                    }).create();
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
    })

});

