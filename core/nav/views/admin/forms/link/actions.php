<input type="submit" name="submit" value="<?=lang('save')?>" class="btn btn-primary" />

<?php if( $form->is_update() ):?>
    <?=URL::anchor('admin/nav', 'Создать ссылку', array('class'=>'btn'))?>
<?php endif?>