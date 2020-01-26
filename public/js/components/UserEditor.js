class UserEditor{
      constructor(data){
          this.data = data;
          this.mainPhoneData;
          this.addPhoneData;
      }

      render(){
          let self = this;
          $.ajax({
              type: 'post',
              url: '/showContactPhone',
              data: this.data,
              success: function(response) {
                  for(let i in response){
                      let phone = new PhoneEditor(response[i]);
                      if(response[i].primary) self.mainPhoneData = response[i];
                      if(!response[i].primary) self.addPhoneData = response[i];
                      phone.render(`${response[i].primary ? '.main-phone' : '.additional-phone' }`);
                  }
              }
          });

          $.ajax({
                  type: 'post',
                  url: `/getStatusLead`, 
                  data: {'contactId': self.data.id},
                  success: function (response) {
                        let statusName =  response == 15 ? 'Оплачен' : 
                                          response == 14 ? 'Выставлен счёт' : 
                                          response == 13 ? 'Предложение' : 
                                          response == 12 ? 'Замер' : 
                                          response == 11 ? 'Обработка' : 'Новый' ;
                        $('#stausAvatar').prepend(`<img src="/img/icons/status-${response}.png">`);
                        $('#statusName').text(statusName);
                  }
          });
      }

      update(){
            let self = this;
            let bio = self.changeUserBio();
            let mainPhone = self.changePhone(self.mainPhoneData);
            let addPhone = self.changePhone(self.addPhoneData);

            $.ajax({
                  type: 'PATCH',
                  url: `/contact/${parseInt(self.data.id)}`,
                  data: bio,
                  success: function(response) {
                        console.log(response);
                        let note = new Notes({
                              status: 'success',
                              content: response,
                              timer: '2000'
                          }).create();
                  }
            });

            $.ajax({
                  method: 'post',
                  url: `/contactPhoneUpdate`,
                  data: mainPhone,
                  success: function(response) {
                        console.log(response);
                  }
            });
            if(addPhone){
                  $.ajax({
                        method: 'post',
                        url: `/contactPhoneUpdate`,
                        data: addPhone,
                        success: function(response) {
                              console.log(response);
                        }
                  });
            }else if(self.addPhoneData && !addPhone){
                  $.ajax({
                        method: 'DELETE',
                        url: `/contact/${addPhone.id}`,
                        success: function(response) {
                              console.log(response);
                        }
                  });
            }
      }

      updateAdmin(){
            let self = this;
            let data ={
                  user_id: $('#manager_id').val(),
                  group_id: $('#group_id').val(),
                  contactAdditionalFormEdit: 1,
            }
            $.ajax({
                  type: 'PATCH',
                  url: `/contact/${parseInt(self.data.id)}`,
                  data: data,
                  success: function (response) {
                        console.log(response)
                  }
            });
      }

      updateName(){
            let self = this;
            let request = {fio: $(`#fio`).val()};

            $.ajax({
                  type: 'PATCH',
                  url: `/contact/${parseInt(self.data.id)}`,
                  data: request,
                  success: function(response) {
                        console.log(response);
                  }
            });
      }
      
      changePhone(data){
            let self = this;
            let request;
            if(!data) {
                  (() =>{
                        request = {
                              contact_id: self.data.id,
                              phone: $('#addContactPhone').val(),
                              primary: 0,
                              messangers:[
                                    {
                                          name: $('.additional-phone select').val(),
                                    }
                              ]
                        }
                  })();
            }else{
                  (() =>{
                        request = {
                              id: data.id ,
                              contact_id: data.contact_id,
                              phone:  (data.primary) ? $('#mianContactPhone').val() :  $('#addContactPhone').val(),
                              primary: data.primary,
                              messangers:[
                              {
                                    name: (data.primary) ? $(`.main-phone select`).val(): $('.additional-phone select').val(),
                                    status: 1,
                                    phone_id: data.id,
                              }
                              ]
                        }
                  })(data);
            }
            
            return request;
      }

      changeUserBio(){
          let request;
          (() => {
              request = {
                  fio: $(`#fio`).val(),
                  region_id: $(`#region_id`).val() ,
                  city: $(`#city`).val(),
                  email: $(`#email`).val(),
                  age: $(`#age`).val(),
                  gender: $(`#gender`).val(),
                  sources_id: $(`#sources_id`).val(),
                  contact_quality_id: $(`#contact_quality_id`).val(),
                  price_category_id: $(`#price_category_id`).val(),
                  contactFormEdit: true
              }
          })()
          return request;
      }
  }