<div class="control-group">
    <label class="control-label" for="">
        <?php echo $label?>:
    </label>
    <div class="controls">
        <?php if( isset($value) ):?>
            <div>
                <?=$value?>
            </div>
        <?php endif;?>
        <input type="file" name="<?php echo $field?>" />
        <?php if($help):?>
            <p class="help-block"><?php echo $help?></p>
        <?php endif;?>
    </div>
</div>