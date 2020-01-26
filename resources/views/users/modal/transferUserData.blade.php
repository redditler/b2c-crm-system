<div class="modal fade" id="transferCases" tabindex="-1" role="dialog" aria-labelledby="transferCasesLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="transferCasesTitle">Передать дела</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div id="transferCaseUserGroup">
                        <label for="transferCasesUserGroupOption">Группа</label>
                        <select class="form-control" name="user_group" required id="transferCasesUserGroupOption">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div id="transferCaseChooseUser">
                        <label for="transferCaseChooseUserOption">Сотрудник</label>
                        <select class="form-control" name="user_id" required id="transferCaseChooseUserOption">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-default" id="transferCasesManagerButton">Передать</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="transferCasesManagerClose" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>