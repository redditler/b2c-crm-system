class Comment {
      constructor(data){
            this.data = data;
      }

      render(data = false){
            data = (data) ? data : this.data
            let el  =  `<div class="comment" data-create="${data.updated_at}">
                              <div class="comment__header">
                                    <div class="comment__create-at">
                                          <span class="create-at__value">${data.create_at}</span>
                                    </div>
                                    <div class="comment__autor">
                                          <span class="autor__value">${data.autor_name}</span>
                                    </div>
                              </div>
                              <div class="comment__content">
                                    <span class="content__value">${data.content}</span>
                              </div>
                        </div>`
            return el;
      }
}