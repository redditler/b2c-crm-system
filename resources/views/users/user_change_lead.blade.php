<!-- Button trigger modal -->
<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#transferCases">
    Передать дела
</button>

<!-- Modal -->
<div class="modal fade" id="transferCases" tabindex="-1" role="dialog" aria-labelledby="transferCasesLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="transferCasesLabel">Передать дела</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" name="_token" id="userTransferToken" value="{{csrf_token()}}">
                    <input type="hidden" name="_token" id="transferCasesManagerOld" value="{{$user->id}}">
                    <label for="changeManager">Выбирите мененджера для передачи дел
                        <select class="form-control" id="transferCasesManager">
                            <option selected disabled>Выбирите мененджера</option>
                            @foreach(\App\User::query()->where('role_id', 3)->get() as $value )
                                <option value="{{$value->id}}">{{$value->name}}</option>
                            @endforeach
                        </select>
                    </label>
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