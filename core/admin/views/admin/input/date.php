<div class="control-group">
    <label class="control-label" for="">
        <?php if( $help ):?>
            <span class="input-help" data-content="<?=$help?>"><?=$label?></span>
        <?php else:?>
            <?=$label?>
        <?php endif?>
    </label>
    <div class="controls">
        <input type="text" name="<?php echo $name?>" value="<?php echo $value?>" <?php echo Tag::parse_attributes($attr)?> />
        <?php if($help):?>
            <p class="help-block"><?php echo $help?></p>
        <?php endif;?>
    </div>
</div>

<script>
    $(function(){
        $("input[name=<?=$name?>]").datepicker(
            <?=json_encode($js_settings)?>
        ); 
    });
</script>