$(document).ready(function () {

    $('#sectorGroupFilter').delegate('select', 'change', function () {
        statisticsTable.ajax.reload();
    });

    $('#sectorRegionManagerFilter ').delegate('select', 'change', function () {
        statisticsTable.ajax.reload();
    });

    $('#sectorSalonFilter ').delegate('select', 'change', function () {
        statisticsTable.ajax.reload();
    });

    let statisticsTable = $('#summaryReportTable').DataTable({
        order: [['1', "desc"]],
        "lengthMenu": [100, 250, 500],
        "pageLength": 100,
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            "url": '/statisticsDate',
            "method": "POST",
            'data': function (d) {
                d.dateFrom = $('#statDateFrom').val();
                d.dateTo = $('#statDateTo').val();

                d.group_id = $('#leadGroupSelector').val();
                d.regionManager_id = $('#leadRegionManagerSelector').val();
                d.salon_id = $('#leadSalon').val();

            }
        },
        columns: [
            {data: 'branch_id', name: 'branch_id', width: '10%'},
            {data: 'frameworks', name: 'frameworks', width: '5%'},
            {data: 'frameworks_sum', name: 'frameworks_sum', width: '5%'},
            {data: 'count_clients', name: 'count_clients', width: '5%'},
            {data: 'count_in_calls', name: 'count_in_calls', width: '5%'},
            {data: 'count_out_calls', name: 'count_out_calls', width: '5%'},
            // {data: 'count_lost_calls', name: 'count_lost_calls', width: '5%'},
            // {data: 'count_culations', name: 'count_culations', width: '5%'},
            // {data: 'common_culations', name: 'common_culations', width: '5%'},
            // {data: 'direct_sample', name: 'direct_sample', width: '5%'},
            // {data: 'count_framework_culations', name: 'count_framework_culations', width: '5%'},
            // {data: 'count_bills', name: 'count_bills', width: '5%'},
            // {data: 'count_framework_bills', name: 'count_framework_bills', width: '5%'},
            // {data: 'common_sum_bills', name: 'common_sum_bills', width: '5%'},
            {data: 'count_payments', name: 'count_payments', width: '5%'},
            {data: 'count_framework_payments', name: 'count_framework_payments', width: '5%'},
            {data: 'common_sum_payments', name: 'common_sum_payments', width: '5%'},
            {data: 'frameworks_percent', name: 'frameworks_percent', width: '5%'},
            {data: 'frameworks_sum_percent', name: 'frameworks_sum_percent', width: '5%'},
        ],
        "footerCallback": function (tfoot, data, start, end, display) {

            let api = this.api();

            $.ajax({
                method: 'post',
                url: '/statisticsSumDate',
                data: {
                    dateFrom: $('#statDateFrom').val(),
                    dateTo: $('#statDateTo').val(),

                    group_id : $('#leadGroupSelector').val(),
                    regionManager_id : $('#leadRegionManagerSelector').val(),
                    salon_id : $('#leadSalon').val()

                },
                success: function (result) {
                    // console.log(result)

                    for (let i = 1; i <= result.length; i++) {
                        $(api.column(i).footer()).html('<b>' + result[i - 1] + '</b>')
                    }
                }
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

    $("#statDateFrom").change(function () {
        statisticsTable.ajax.reload();
    });
    $("#statDateTo").change(function () {
        statisticsTable.ajax.reload();
    });




});