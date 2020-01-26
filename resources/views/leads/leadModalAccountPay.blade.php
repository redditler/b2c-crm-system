<div class="modal fade modal-account-pay" id="leadModalAccountPay" tabindex="-1" role="dialog" aria-labelledby="leadModalAccountPay">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal--title" id="leadModalAccountPayLabel">Введите номер и дату заказа:</h4>
            </div>
            <div class="modal-body" id="leadModalAccountPayContent">
                    <div class="form-group">
                        <label><span class="form--title">Номер заказа:</span>
                            <input type="text" class="form-control input-sm" pattern="[0-9]{6}" id="leadModalAccountPayNumber" name="accountPayNumber" value="" placeholder="Введите 6 цифр">
                        </label>
                    </div>
                    <div class="form-group">
                        <label><span class="form--title">Дата заказа:</span>
                            <input type="date" class='form-control filter--date' id="leadModalAccountPayDate" name="accountPayDate" value="">
                        </label>
                    </div>
            </div>
            <div id="leadModalAccountPayResult"></div>
            <div id="leadAccountPayResult"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn--success" id="leadModalAccountPayConfirm">Подтвердить</button>
                <button type="button" class="btn btn--close" data-dismiss="modal" id="leadModalAccountPayClose">Закрыть</button>
            </div>
        </div>
    </div>
</div>
