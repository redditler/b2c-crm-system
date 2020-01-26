<!-- Large modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-addContactQuality-modal-lg">Создать квалификацию</button>

<div class="modal fade bs-addContactQuality-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="padding: 15px">
            <form id="addContactQuality">
                {{csrf_field()}}
                <div class="form-group">
                    <label for="addContactQualityId">Название квалификации:</label>
                    <input class="form-control" id="addContactQualityId" type="text" name="title" required placeholder="Введите название квалификации">
                </div>
                <div class="form-group">
                    <label for="addContactQualityId">Описание квалификации:</label>
                    <input class="form-control" id="addContactQualityId" type="text" name="description" required placeholder="Введите описание квалификации">
                </div>
                <div class="form-group">
                    <input class="btn btn-success" id="addContactQualitySubmit" type="submit" value="Создать">
                </div>
            </form>
        </div>
    </div>
</div>