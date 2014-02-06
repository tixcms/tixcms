<div class="control-group">
    <label class="control-label" for="">
        <?php if( $help ):?>
            <span class="input-help" data-content="<?=$help?>"><?=$label?></span>
        <?php else:?>
            <?=$label?>
        <?php endif?>
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