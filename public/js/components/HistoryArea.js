class HistoryArea{
      constructor(data){
            this.data = data;
      }

      render(){
            let self = this;
            $.ajax({
                  type: 'post',
                  url: '/showHistory',
                  data: this.data,
                  success: function(response) {
                        self.createList(response.data);
                  }
            });
      }

      createList(data){
            let date = false;

            for(let i in data){
                  let currentDate = new Date(data[i].updated_at.split(' ')[0]);
                  if(date < currentDate){
                        let dateForShow = `${currentDate.getDate()}.${currentDate.getMonth() + 1}.${currentDate.getFullYear()}`
                        let nowDate = new Date();
                        nowDate = `${nowDate.getDate()}.${nowDate.getMonth() + 1}.${nowDate.getFullYear()}`;
                        $('#historyArea').append(`<div class="date-budge"><span class="budge__value">${dateForShow == nowDate ? 'Сегодня' : dateForShow}</span></div>`);
                        date = currentDate;
                  }

                  let content = {
                        create_at: data[i].created_at.split(' ')[1],
                        updated_at: data[i].updated_at.split(' ')[0],
                        autor_name: data[i].user_id,
                        content: data[i].description
                  }

                  let el = new Comment(content);
                  $('#historyArea').append(el.render());
            }

            let block = document.getElementById('historyArea');
            block.scrollTop = block.scrollHeight;
      }
}