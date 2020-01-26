class Notes {
      constructor(data){
            this.data = data;
      }

      create(){
            let el =   `<div id="notes" class="notes notes-${this.data.status}"">
                              <div class="notes__content">
                                    <p class="content__text">
                                          ${this.data.content}
                                    </p>
                              </div>
                        </div>`;
            
            
            $('.content-wrapper').append(el);

            setTimeout(() =>  $("#notes").addClass('show') , 300);
            setTimeout(() =>  $("#notes").removeClass('show') , this.data.timer);
            setTimeout(() =>  $("#notes").remove() , this.data.timer + 300);
      }
}
