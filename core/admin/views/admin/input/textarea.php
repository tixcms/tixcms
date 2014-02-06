<div class="control-group field-<?=$field?> <?=$form->error($field) ? 'error' : ''?>">
    <label class="control-label" for="">
        <?php if( $help ):?>
            <span class="input-help" data-content="<?=$help?>"><?=$label?></span>
        <?php else:?>
            <?=$label?>
        <?php endif?>
    </label>
    <div class="controls">
        <?=HTML\Tag::textarea($field, $value, $attrs)?>
        <?=isset($after_input) ? $after_input : ''?>
        <?php if( $form->show_inline_errors() ):?>
            <span class="error-string error-field-<?=$field?>"><?=$form->error($field)?></span>
        <?php endif?>
    </div>
</div>