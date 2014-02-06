<form <?=$form->render('attrs')?>>
    <div class="modal hide" id="create" style="width: 660px;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Создание категории</h3>
        </div>
        <div class="modal-body form-horizontal">
            <?=$form->render('inputs')?>
        </div>
        <div class="modal-footer">
            <?=$form->render('actions')?>
        </div>
    </div>
</form>