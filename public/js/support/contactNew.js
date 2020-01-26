$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}
});
function _contactUpdate(name,contact) {
    $(name).on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'PATCH',
            url: `/contact/${contact.id}`,
            data: $(name).serializeArray(),
            success: function (result) {
                console.log(result);
                historyTable.ajax.reload();
            }
        });
    });
}

export {_contactUpdate};