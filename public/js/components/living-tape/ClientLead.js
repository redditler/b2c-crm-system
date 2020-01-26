class ClientLead{
      constructor(data, selctor){
            this.data = data;
      }

      render(){
            $.ajax({
                  method: 'post',
                  url: '/oneClientLead',
                  data: {id: this.data},
                  success: function (result) {
                        $(`#leadOneClientModalContentTBody`).html('');
                        for (let index in result.reverse()) {
                              $(`#leadOneClientModalContentTBody`).append(
                                    `<tr>
                                          <td>${result[index].created_at}</td>
                                          <td>${result[index].region.name}</td>
                                          <td>${result[index].leed_name}</td>
                                          <td>${result[index].leed_phone}</td>
                                          <td>
                                                <div class="status-tilte">${getName(result[index].status.id)}</div>
                                                <div class="progress-column progress-${getStatus(result[index].status.id)}"></div>
                                          </td>
                                    </tr>`
                              );
                        }
                        progressBar()
                  }
            })

            const getStatus = (number) =>{
                  if(number == 5) return 1
                  else if(number == 11) return 2
                  else if(number == 12) return 3
                  else if(number == 13) return 4
                  else if(number == 14) return 5
                  else if(number == 15) return 6
            }

            const getName = (number) =>{
                  if(number == 5) return 'Новый'
                  else if(number == 11) return 'Обработка'
                  else if(number == 12) return 'Замер'
                  else if(number == 13) return 'Предложение'
                  else if(number == 14) return 'Выставлен счёт'
                  else if(number == 15) return 'Оплачен'
            }
      }
}