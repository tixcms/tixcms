<form <?=HTML\Tag::parse_attributes($attrs)?> <?=$form->render('attrs')?>>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>
            <?=$header?>
        </h3>
    </div>
    <div class="modal-body">
        <?=$form->render('inputs')?>
    </div>
    <div class="modal-footer">
        <?=$form->render('actions')?>
    </div>
</form>