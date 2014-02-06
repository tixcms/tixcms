<div class="control-group">
    <label class="control-label" for="<?=$field?>">
        <?php if( $help ):?>
            <span class="input-help" data-content="<?=$help?>"><?=$label?></span>
        <?php else:?>
            <?=$label?>
        <?php endif?>
    </label>
    <div class="controls">
        <label class="checkbox">
            <input 
                type="checkbox" 
                name="<?=$field?>"
                id="<?=$field?>" 
                <?=(isset($value) AND $value == TRUE) ? 'checked="checked"' : ''?>
                <?=HTML\Tag::parse_attributes($attrs)?> 
            />
        </label>
    </div>
</div>