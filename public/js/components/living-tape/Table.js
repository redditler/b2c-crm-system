class Table {
      render(){
            return $('#leads').DataTable({
                  order: [['1', "desc"]],
                  processing: true,
                  serverSide: true,
                  ajax: {
                        "url": '/indexAddEditRemoveColumnData',
                        "method": "POST",
                        'data': (data)=>{
                              data.leadDateFrom= $('#leadDateFrom').val();
                              data.leadDateTo= $('#leadDateTo').val();
                              data.group_id= $('#leadGroupSelector').val();
                              data.regionManager_id= $('#leadRegionManagerSelector').val();
                              data.salon_id= $('#leadSalon').val();
                              data.user_id= $('#leadManagerSelector').val();
                              data.leadStatusId= JSON.parse($('#funnel').attr('data-status'));
                        }
                  },
                  columnDefs: [{ "targets": 0,}],
                  deferRender: true,
                  columns: (() =>{
                        let columms =  [
                              {data: 'leed_receive_id', orderable: false, render: function (data) {
                                          return `<img src="img/icons/lead-icon-${data}.svg">`;
                                    }
                              },
                              {data: 'created_at', name: 'created_at', searchable: 'true'},
                              {data: 'region', name: 'region', orderable: false},
                              {data: 'leed_name', name: 'leed_name', orderable: false},
                              {data: 'leed_phone', name: 'leed_phone', orderable: false},
                              {data: 'status', name: 'status', orderable: false},
                              {data: 'manager', name: 'manager', orderable: false, searchable: false},
                              {data: 'comment', name: 'comment', orderable: false, searchable: false},
                        ];
                        if($('#userRoleId').val() == 5){
                              columms.push({data: 'managerCall',name: 'managerCall',orderable: false,searchable: false,});
                        };
                        if($('#userManager').val()){
                              columms.push(
                                    {data: 'btns', name: 'btns',orderable: false,searchable: false,},
                                    {data: 'btnDefect', name: 'btnDefect',orderable: false,searchable: false,}
                              )
                        }
                        return columms;
                  })(),
                  fixedHeader: {
                      header: true 
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
                  }
            });
      }
}