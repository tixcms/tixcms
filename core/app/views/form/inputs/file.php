<div class="control-group">
    <label class="control-label" for="">
        <?=$label?>:
    </label>
    <div class="controls">
        <input type="file" name="<?=$field?>" />
        <?php if($help):?>
            <p class="help-block"><?php echo $help?></p>
        <?php endif;?>
    </div>
</div>