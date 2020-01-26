class Funnel {
      render(){
            $.ajax({
                  type: 'post',
                  url: `/leadFilterAll`,
                  data: {
                        leadDateFrom: $('#leadDateFrom').val(),
                        leadDateTo: $('#leadDateTo').val(),
                  },
                  success: function (response){
                       let arr = response.map((item)=> item.status_id).sort((a,b)=> a-b);
                       $(`#all .val--text`).text(arr.length);
                       $(`#new .val--text`).text(arr.filter(item => item == 5).length);
                       $(`#processing .val--text`).text(arr.filter(item => item == 11).length);
                       $(`#measurement .val--text`).text(arr.filter(item => item == 12).length);
                       $(`#offer .val--text`).text(arr.filter(item => item == 13).length);
                       $(`#waiting .val--text`).text(arr.filter(item => item == 14).length);
                       $(`#complete .val--text`).text(arr.filter(item => item == 15).length);
                  }
            });
      }

      reload(){
            this.render()
      }
}

