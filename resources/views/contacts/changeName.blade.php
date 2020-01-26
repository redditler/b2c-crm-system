<div class="modal fade bs-changeName-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="content__header">
                        <h3 class="title">Введите правильное имя клиента</h3>
                  </div>
                  <div class="content__body">
                        <input name="fio" class="form-control" id="fio" placeholder="Ф.И.О." value="{{$contact->fio}}">
                  </div>
                  <div class="content__footer">
                        <button type="button" class="btn btn--default" data-dismiss="modal">Закрыть</button>
                        <button type="button" id="changeName" class="btn btn--default blue">Изменить</button>
                  </div>
            </div>
      </div>
</div>