<div class="control-group">
    <label class="control-label" for="">
        <?php if( $help ):?>
            <span class="input-help" data-content="<?=$help?>"><?=$label?></span>
        <?php else:?>
            <?=$label?>
        <?php endif?>
    </label>
    <div class="controls">
        <input type="file" name="<?=$field?>" />
        <?php if($help):?>
            <p class="help-block"><?php echo $help?></p>
        <?php endif;?>
        
        <div>
            <a href="<?=URL::site_url(\Turfirmam\Settings::UPLOAD_PATH . $value)?>">
                <?=$value?>
            </a>
        </div>
    </div>
</div>