<div class="control-group">
    <label class="control-label" for="">
        <?=isset($label) ? $label : ''?>
    </label>
    <div class="controls">
        <input 
            type="password" 
            name="<?=$field?>" 
            value="<?=$value?>"
            <?=HTML\Tag::parse_attributes($attrs)?>
            <?=isset($placeholder) ? 'placeholder="'. $placeholder .'"' : ''?>
        />
        <br />
        <?=URL::anchor('users/reset', 'Забыли пароль?', array('style'=>'margin-left: 115px;'))?>
        <?php if($help):?>
            <p class="help-block"><?php echo $help?></p>
        <?php endif;?>
    </div>
</div>