<div class="control-group">
    <?php if( !isset($placeholder) OR !$placeholder ):?>
        <label class="control-label" for="">
            <?=isset($label) ? $label : ''?>
        </label>
    <?php endif?>
    <div class="controls">
        <input 
            type="text" 
            name="<?=$field?>" 
            value="<?=$value?>"
            <?=HTML\Tag::parse_attributes($attrs)?>
            <?=isset($placeholder) ? 'placeholder="'. $placeholder .'"' : ''?>
        />
        <?php if($help):?>
            <p class="help-block"><?php echo $help?></p>
        <?php endif;?>
    </div>
</div>