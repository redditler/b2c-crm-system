class PhoneEditor{
      constructor(data){
          this.data = data;
      }

      render(selector){
          let props = this.data;
          $(`${selector} input`).val(props.phone);
          $(`${selector} select`).val(props.messangers.length > 0 ? props.messangers[0].name  : 'sms');
          $(`${selector} .select-icon`).attr('data-id', (props.messangers.length > 0 ? props.messangers[0].name  : 'sms'));

          this.change(selector);
      }

      change(selector){
          $(`${selector} select`).on('change', () => {
              $(`${selector} .select-icon`).attr('data-id', $(`${selector} select`).val());
          })
      }
}
