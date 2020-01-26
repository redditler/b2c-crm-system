$(document).ready(function () {
        $('#transferCasesManagerButton').on('click', function (e) {
            e.preventDefault();

            $.ajax({
                url: '/user_transfer',
                method: 'post',
                data:{
                    _token : $('#userTransferToken').val(),
                    old_user_id: $('#transferCasesManagerOld').val(),
                    user_id: $('#transferCasesManager').val()
                },
                success: function (result) {
                    alert(result);
                    $('#transferCasesManagerClose').click();

                }

            })

        })
});
