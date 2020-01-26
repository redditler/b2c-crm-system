class contactList {
      constructor(arr){
            this.arr = arr;
      }

      createData(){
            let data = this.arr.map(item =>{
                  item.id = item.id;
                  item.date = item.date == "В таблице отсутствуют данные" ? "Клиент не найден!" : `${item.date[0] + item.date [1]} / ${item.date[3] + item.date[4]} / ${item.date[6] + item.date[7] + item.date[8] + item.date[9]}`;
                  item.region = item.region ? item.region : '------' ;
                  item.name = item.name ? item.name : '-------';
                  item.phone = item.phone ? item.phone : '-------';
                  item.statusName = (item.statusName  == 5) ? 'Новый' : (item.statusName == 11) ? 'Обработка' : (item.statusName == 12) ? 'Замер' : (item.statusName == 13) ? 'Предложение' : (item.statusName == 14) ? 'Выставлен счёт' : 'Оплачен';
                  item.statusId = (item.statusId  == 5) ? '1' : (item.statusId == 11) ? '2' : (item.statusId == 12) ? '3' : (item.statusId == 13) ? '4' : (item.statusId == 14) ? '5' : '6'; 
                  item.quality = item.quality;
            })
            return data;
      }

      createList(selector){
            let data = this.createData();
            let list = '';
            if(data.length == 1 && !data[0]){
                  $(selector).append('<h3 style="width: 100%; text-align: center;">Клиент не найден!</h3>');
            }else{
                  for(let i in data){
                        let card = new Card(this.arr[i]);
                        list += card.createCard();
                  }
                  $(selector).append(list);
      
                  let colorList = ['#77c5d2', '#3dafc7', '#54a1b1', '#438792', '#366971', '#666666'];
      
                  for (let i = 0; i < 6; i++) {
                        for (let p = 0; p < i + 1; p++) {
                        let selectorCurrent = 'div.status-' + (i + 1) + ' div.progres-' + (p + 1);
                        $(selectorCurrent).css('background-color', colorList[p]);
                        }
                  }
            }
            
      }
}

