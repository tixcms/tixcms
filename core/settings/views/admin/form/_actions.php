<?php if(  CI::$APP->module->url != 'settings' AND $this->action == 'settings'  ):?>
    <?=\URL::anchor('admin/'. $this->module, 'Обратно', array(
        'class'=>'btn'
    ))?>
<?php endif?>

<input type="submit" name="submit" value="Сохранить" class="btn btn-primary" />