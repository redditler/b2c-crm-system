const colorpicker = (selector) =>{
      let el = '';

      el = `<div class="colorpicker__label">
                  <div class="current-value" style="background: #BFDAE1;"></div>
            </div>

            <div class="colorpicker__wrapper hide">
                  <div class="color" data-id="#BFDAE1" style="background: #BFDAE1;"></div>
                  <div class="color" data-id="#9CDCE9" style="background: #9CDCE9;"></div>
                  <div class="color" data-id="#46C5E0" style="background: #46C5E0;"></div>

                  <div class="color" data-id="#88B0B5" style="background: #88B0B5;"></div>
                  <div class="color" data-id="#4F8792" style="background: #4F8792;"></div>
                  <div class="color" data-id="#727272" style="background: #727272;"></div>

                  <div class="color" data-id="#E5B3A7" style="background: #E5B3A7;"></div>
                  <div class="color" data-id="#DE3F18" style="background: #DE3F18;"></div>
                  <div class="color" data-id="#363032" style="background: #363032;"></div>
            </div>`
      $('.colorpicker').append(el);

      $('body').on('click', '.colorpicker__label', function(){
            $('.colorpicker__wrapper')
                  .toggleClass('hide')
                  .toggleClass('show');
      });

      $(document).mouseup(function (e){ 
            var div = $(".colorpicker__wrapper"); 
            if (!div.is(e.target) && div.has(e.target).length === 0) { 
                  div.removeClass('show'); 
                  div.addClass('hide')
            }
      });

      $('body').on('click', '.color', function(){
            $('.colorpicker__label .current-value').css('background', $(this).attr('data-id'));
      })
};

