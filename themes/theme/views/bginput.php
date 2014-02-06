<div class="control-group field-<?=$field?> <?=$form->error($field) ? 'error' : ''?>">
    <label class="control-label" for="<?=$field?>">
        <?php if( $help ):?>
            <span class="input-help" data-content="<?=$help?>"><?=$label?></span>
        <?php else:?>
            <?=$label?>
        <?php endif?>
    </label>
    <div class="controls">
    
        <div style="background: white; padding: 10px; display: inline-block;">
    
            <?php foreach($options as $key=>$name):?>
                
                <div class="bg-square-<?=$field?> <?=$key == $value ? ' active' : ''?>" 
                    title="<?=$name?>" 
                    data-value="<?=$key?>" 
                    style="background: url(<?=URL::site_url('themes/theme/img/'. $folder .'/'. $key .'.png')?>);"
                ></div>
                
            <?php endforeach?>
        
        </div>
    
        <input type="hidden" name="<?=$field?>" value="<?=$value?>" />
    
        <?php if( $form->show_inline_errors() ):?>
            <span class="error-string error-field-<?=$field?>"><?=$form->error($field)?></span>
        <?php endif?>
        <?=isset($after_input) ? $after_input : ''?>
    </div>
</div>

<style>
    .bg-square-<?=$field?> {
        width: 60px;
        height: 60px;
        border: 1px solid #999;
        display: inline-block;
        cursor: pointer;
    }
    .bg-square-<?=$field?>.active {
        outline: 2px solid orange;
    }
</style>

<script>
    $(function(){
        $('.bg-square-<?=$field?>').click(function(){
            $(".bg-square-<?=$field?>").removeClass('active');
            $(this).addClass('active');
            
            var value = $(this).data('value');
            $("[name=<?=$field?>]").val(value); 
        });
    });
</script>