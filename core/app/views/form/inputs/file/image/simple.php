<div class="control-group">
    <label class="control-label" for="">
        <?=$label?>:
    </label>
    <div class="controls">
        <?php if( isset($value) AND $value ):?>
            <?=HTML\Tag::img($value)?>
            <br />
        <?php endif;?>
        <input type="file" name="<?=$field?>" />
        <?php if($help):?>
            <p class="help-block"><?=$help?></p>
        <?php endif;?>
    </div>
</div>