<div class="control-group">
    <label class="control-label" for="">
        <?php echo $label?>:
    </label>
    <div class="controls">
        <input type="file" name="<?php echo $name?>" />
        <?php if($help):?>
            <p class="help-block"><?php echo $help?></p>
        <?php endif;?>
    </div>
</div>