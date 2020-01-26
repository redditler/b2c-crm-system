{{--<button class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Большая модаль</button>--}}

<div class="modal fade bs-restart2fa-modal-lg" tabindex="-1" role="dialog" aria-labelledby="restart2faModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Подтвердите дейсвие</h4>
            </div>
            <div class="modal-body">
                <p>Вы уверены что хотите сбросить Google Authenticator</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btnRestart2faModal">Да</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Нет</button>
            </div>
            <div id="restart2faModalResult"></div>
        </div>
    </div>
</div>
<div class="modal fade bs-fired-modal-lg" tabindex="-1" role="dialog" aria-labelledby="firedUserModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Подтвердите дейсвие</h4>
            </div>
            <div class="modal-body">
                <p>Вы уверены что хотите уволить сотрудника <span id="checkUserFiredModal"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btnFiredUserModal">Да</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Нет</button>
            </div>
            <div id="firedUserModalResult"></div>
        </div>
    </div>
</div>
<div class="modal fade bs-deleteUser-modal-lg" tabindex="-1" role="dialog" aria-labelledby="deleteUserModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Подтвердите дейсвие</h4>
            </div>
            <div class="modal-body">
                <p>Вы уверены что хотите удалить сотрудника <span id="checkUserFiredModal"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btnDeleteUserModal">Да</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Нет</button>
            </div>
            <div id="deleteUserModalResult"></div>
        </div>
    </div>
</div>
