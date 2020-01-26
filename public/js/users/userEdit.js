$(document).ready(function () {

    $('#user_group').change(function (e) {
        userEditBranch();
    });

    function userEditBranch() {
        $.ajax({
            url: '/userEditBranch',
            method: 'post',
            data: {
                groupId: $('#user_group').val()
            },
            success: function (result) {
                $('#user_branch').html('');
                if (!$.isEmptyObject(result)) {
                        $('#user_branch').html(`<option id="first_option" selected disabled>Выберите салон</option>`);

                    result.forEach(function (element) {
                        $('#first_option').after(`<option value="${element.id}">${element.name}</option>`);
                    });
                }
            }
        });
    }

    userEditBranch();

    $('#userEditSubmit').on('click', function (e) {
        e.preventDefault();

        $.ajax({
            method: 'post',
            url: `/users/${$('#user_id').val()}`,
            data: $('#userEdit').serializeArray(),
            success: function (result) {
                $('#userUpdateResult').html('')
                $('#userUpdateResult').html(result)

            }
        });

    })
});
