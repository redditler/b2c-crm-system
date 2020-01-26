$(document).ready(function () {

    let workUser = $('#tableUserWork').DataTable({
        order: [['1', "desc"]],
        "lengthMenu": [ 100, 250, 500 ],
        "pageLength": 100,
        processing: true,
        serverSide: true,
        ajax: {
            "url": '/userShowTable',
            "method": "POST",
            'data': function (d) {
                d.fired = $('#userWork').val();
                d.group = $('#userGroup').val();
            }
        },
        columns: [
            {data: 'id', name: 'id', width: '7%'},
            {data: 'name', name: 'name'},
            {data: 'group_id', name: 'group_id', orderable: false},
            {data: `role_id`, name: 'role_id', orderable: false},
            {data: '2fa', name: '2fa'},
            {data: `action`, name: `action`},
        ]
        ,
        "drawCallback": function (setting) {
            $('.checkboxGoogle2fa').change(function (e) {
                let checkbox2FA = $(this);
                $.ajax({
                    method: 'post',
                    url: '/changeUser2fa',
                    data: {
                        id: checkbox2FA.val(),
                        checked: checkbox2FA.is(':checked')
                    },
                    success: function (result) {
                       // console.log(result)
                    }
                });
            });

            $('.drop2FAUser').on('click', function (e) {
                e.preventDefault();
                let userId = $(this).val();
                $('.bs-restart2fa-modal-lg').modal('toggle');
                $('.btnRestart2faModal').on('click', function (elem) {

                    $.ajax({
                        method: 'post',
                        url: '/restartUser2fa',
                        data: {id: userId},
                        success: function (result) {
                            $('#restart2faModalResult').html('');
                            $('#restart2faModalResult').html(result);
                            workUser.ajax.reload();
                        }
                    })
                });


            });

            $('.firedUser').on('click', function (e) {
                e.preventDefault();
                let userId = $(this).val();
                $('.bs-fired-modal-lg').modal('toggle');
                $('.btnFiredUserModal').on('click', function (elem) {

                    $.ajax({
                        method: 'post',
                        url: '/firedUser',
                        data: {id: userId},
                        success: function (result) {
                            $('#firedUserModalResult').html('');
                            $('#firedUserModalResult').html(result);
                            workUser.ajax.reload();
                        }
                    })
                });

            });

            $('.transferCasesUser').on('click', function (e) {
                e.preventDefault();
                let  old_user_id = $(this).val();

                $('#transferCases').modal('show');
                let chooseGroupUser = $.ajax({
                    url: '/userAllGroup',
                    method: 'post',
                    success: function (groupResult) {
                        $('#transferCasesUserGroupOption').html(`<option id="transferCasesUserGroupOptionFirst" selected disabled>Выберите группу</option>`);
                        groupResult.forEach(function (group) {
                            $('#transferCasesUserGroupOptionFirst').after(`<option value="${group.id}">${group.name}</option>`)
                        })
                    }
                });

                $('#transferCasesUserGroupOption').change(function () {
                    chooseUser();
                });

                function chooseUser() {
                    $.ajax({
                        url: '/getUserWitGroup',
                        method: 'post',
                        data: {user_group: $('#transferCasesUserGroupOption').val()},
                        success: function (users) {
                            $('#transferCaseChooseUserOption')
                                .html(`<option  id="transferCaseChooseUserOptionFirst" selected disabled>Выберите сотрудника</option>`);
                            users.forEach(function (user) {
                                $('#transferCaseChooseUserOptionFirst').after(`<option value="${user.id}">${user.name}</option>`)
                            })

                        }
                    });
                }
                chooseUser();
                    // console.log($(this).val());
                $('#transferCasesManagerButton').on('click', function () {
                    
                    $.ajax({
                        url: '/user_transfer',
                        method: 'post',
                        data:{
                            old_user_id: old_user_id,
                            user_id:$('#transferCaseChooseUserOption').val()
                        },
                        success: function (result) {
                            alert(result)
                        }
                    });
                });



            })
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

    $('#userGroup').change(function (e) {
        workUser.ajax.reload();
    });
    $('#userWork').change(function (e) {
        workUser.ajax.reload();
    });


});
