<div class="control-group">
    <label class="control-label" for="">
        <?php echo $label?>:
    </label>
    <div class="controls">
        <?php echo HTML\Tag::textarea($field, $value, $attrs)?>
        <?php if(isset($help)):?>
            <p class="help-block"><?php echo $help?></p>
        <?php endif;?>
    </div>
</div>