<div class="control-group">
    <label class="control-label" for="">
        <?php if( $help ):?>
            <span class="input-help" data-content="<?=$help?>"><?=$label?></span>
        <?php else:?>
            <?=$label?>
        <?php endif?>
    </label>
    <div class="controls">
        <div style="padding-top: 5px;">
            <?=$value?>
        </div>
    </div>
</div>