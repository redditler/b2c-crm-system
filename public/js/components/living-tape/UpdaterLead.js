class UpdaterLead{
      constructor(data, callback){
            this.data = data;
            this.reloadTable = callback;
      }

      update(){
            if(this.data[0].value == 15){
                  this.validatePay(this.data)
            }else{
                  this.updateStatus(this.data);
            }
      }

      updateStatus(data){
            let reloadTable = this.reloadTable;
            $.ajax({
                  url: "/updateLead",
                  type: 'post',
                  data: data,
                  success: function (response) {
                        reloadTable();
                  }
            });
      }

      validatePay(data){
            let self = this;
            $('#leadModalAccountPay').modal('toggle');
            $('#leadModalAccountPayResult').html('');
            $('#leadModalAccountPayNumber').val('');
            $('#leadModalAccountPayDate').val('');

            $('#leadModalAccountPayConfirm').on('click', function (e) {
                  let orderNumber = $('#leadModalAccountPayNumber').val();
                  let orderDate = ($('#leadModalAccountPayDate').val()).split('-');

                 
                  let orderData = {
                        num_zakaz: orderNumber,
                        year: orderDate[0],
                        month: orderDate[1],
                        day: orderDate[2]
                  };

                  delete $.ajaxSettings.headers;

                  $.ajax({
                        url: 'https://dealer.steko.com.ua/tracking.php',
                        type: 'post',
                        secure: false,
                        data: orderData,
                        success: function (e) {
                              $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('#_token').val(),
                                    }
                              });
                              let result = JSON.parse(e);
                              if (result.message == 'not_found' || result.message == 'error') {

                              } else {
                                    self.updateStatus(data);
                                    $('#leadModalAccountPayClose').click();
                                    
                              }
                        }
                  })
            })
      }

      delete(){
            let reloadTable = this.reloadTable;
            let data = this.data;
            $('#discardingLead').modal('toggle');
            $('#discardingLeadSubmit').on('click', function(){
                  let comment = $('#discardingLeadComment').val();

                  $.ajax({
                        type: 'POST',
                        url: '/rejectTrue',
                        data: {
                              id: data[1].value,
                              comment: comment,
                        },
                        success: function(response){
                              $('#discardingLead').click();
                              reloadTable();
                        }
                  })
            });
      }
}