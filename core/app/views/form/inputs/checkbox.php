<div class="control-group">
    <label class="control-label" for="">
        <?=$label?>:
    </label>
    <div class="controls">
        <label class="checkbox">
            <input 
                type="checkbox" 
                name="<?=$field?>" 
                <?=(isset($value) AND $value) ? 'checked="checked"' : ''?>
                <?=HTML\Tag::parse_attributes($attrs)?> 
            />
        </label>
    </div>
</div>