<div class="control-group">
    <label class="control-label" for="">
        <?php echo $label?>:
    </label>
    <div class="controls">
        <input 
            type="text" 
            name="<?=$field?>" 
            value="<?=$value?>"
            <?=HTML\Tag::parse_attributes($attrs)?>
            <?=isset($placeholder) ? 'placeholder="'. $placeholder .'"' : ''?>
        />
        <?php if( isset($help) ):?>
            <span class="help-block"><?=$help?></span>
        <?php endif?>
    </div>
</div>