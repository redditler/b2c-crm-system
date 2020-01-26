$(document).ready(function () {
    //jQuery.noConflict();

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

    $(document).ajaxSuccess(function() {
        $('.multiselect-ui').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            defaultChecked: true,
            nonSelectedText: 'Выбрать',
            selectAllText: 'Все',
            allSelectedText: 'Все'
        });
    });
});
