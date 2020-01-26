class CommentArea {
      constructor(data){
            this.data = data;
      }

      
      render(){
            let self = this;
            $.ajax({
                  type: 'post',
                  url: '/showComment',
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
                        $('#messagesArea').append(`<div class="date-budge"><span class="budge__value">${dateForShow == nowDate ? 'Сегодня' : dateForShow}</span></div>`);
                        date = currentDate;
                  }

                  let content = {
                        create_at: data[i].created_at.split(' ')[1],
                        updated_at: data[i].updated_at.split(' ')[0],
                        autor_name: data[i].user_id,
                        content: data[i].comment
                  }

                  let el = new Comment(content);
                  $('#messagesArea').append(el.render());
            }

            let block = document.getElementById('messagesArea');
            block.scrollTop = block.scrollHeight;
      }

      addComment(){
            let now = new Date();
            let content = {
                  create_at: `${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}`,
                  updated_at: `${now.getFullYear()}:${now.getMonth()}:${now.getDate()}`,
                  autor_name: $('#userName').val(),
                  content: $('#commentInput').val(),
            }

            let el = new Comment(content);
            $.ajax({
                  type: 'post',
                  url: `/addContactComment`,
                  data: {
                      id: parseInt(this.data.id),
                      comment: content.content,
                  },
                  success: function (resultAddComment) {
                        $('#messagesArea').append(el.render());
                        let block = document.getElementById('messagesArea');
                        block.scrollTop = block.scrollHeight;
                        $('#commentInput').val('');
                  }
            });
      }
}