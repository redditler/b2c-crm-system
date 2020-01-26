const progressBar = () => {
      let colorList = ['#77c5d2', '#3dafc7', '#54a1b1', '#438792', '#366971', '#666666'];

      $('.progress-column').html(`
            <div class="column-1"></div>
            <div class="column-2"></div>
            <div class="column-3"></div>
            <div class="column-4"></div>
            <div class="column-5"></div>
            <div class="column-6"></div>`);

      for(let i = 1; i < 7; i++){
            for(let p = 1; p <= i; p++){
                  let selector = `.progress-${i} .column-${p}`;
                  $(selector).css('background', colorList[p-1]);
            }
      }
}