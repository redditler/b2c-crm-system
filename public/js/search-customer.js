jQuery(document).ready(function($) {

    let searchComponent = {
        emptyMessage: "Клиент с таком номером телефона не найден",
        closeBtn: '<div class="close-results"><span>&times;</span></div>',
        SendUrl: '/searchCustomerLead',
        closeWindow: function() {
            $('.result-search').html('').fadeOut(400);
        }
    };

    $('#search-customer').on('keyup', function() {
        let str = $(this).val();
        if(str.length > 2) {
            $.ajax({
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CONFIG_JS.csrfToken },
                url: searchComponent.SendUrl,
                data: {'search': str},
                success: function(data) {
                    let html = searchComponent.closeBtn;
                    if($.isEmptyObject(data)){
                        html += '<div class="emty-message">' + searchComponent.emptyMessage + '</div>';
                    } else {
                        data.forEach(function(contact) {
                            html += '<div class="lead-item"><span>' +
                                contact.phone + '</span> <a href="' + window.location.protocol + '//' + window.location.hostname + '/contact/' + contact.contact_id + '/edit">' +
                                contact.fio + '</a></div>';
                        });
                    }
                    $('.result-search').html(html).fadeIn(400);
                },
                error: function(index, value) {
                    console.log(index, value);
                }
            });
        }
    });

    $('body').on('click', '.close-results',function() {
        searchComponent.closeWindow();
    });
});