<div class="control-group field-<?=$field?> <?=$form->error($field) ? 'error' : ''?>">
    <label class="control-label" for="">
        <?=$label?>:
    </label>
    <div class="controls">
        <?php if( isset($value) AND $value ):?>
            <?=HTML\Tag::img($value)?>
            <br />
        <?php endif;?>
        <input type="file" name="<?=$field?>" />
        <?php if( $form->show_inline_errors() ):?>
            <span class="error-string error-field-<?=$field?>"><?=$form->error($field)?></span>
        <?php endif?>
        <?php if($help):?>
            <p class="help-block"><?=$help?></p>
        <?php endif;?>
    </div>
</div>