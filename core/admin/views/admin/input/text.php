<div class="control-group field-<?=$field?> <?=$form->error($field) ? 'error' : ''?>">
    <label class="control-label" for="<?=$field?>">
        <?php if( $help ):?>
            <span class="input-help" data-content="<?=$help?>"><?=$label?></span>
        <?php else:?>
            <?=$label?>
        <?php endif?>
    </label>
    <div class="controls">
        <input
            id="<?=$field?>"
            type="text"
            name="<?=$field?>" 
            value="<?=$value?>"
            <?=HTML\Tag::parse_attributes($attrs)?> 
            <?=isset($placeholder) ? 'placeholder="'. $placeholder .'"' : ''?>
        />
        <?php if( $form->show_inline_errors() ):?>
            <span class="error-string error-field-<?=$field?>"><?=$form->error($field)?></span>
        <?php endif?>
        <?=isset($after_input) ? $after_input : ''?>
    </div>
</div>