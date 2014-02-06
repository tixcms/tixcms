<div class="input">
    <div class="control-group">
        <label class="control-label">
            Идентификатор поля <br />
            
        </label>
        <div class="controls">
            <input type="text" name="inputs[<?=$i?>][alias]" value="<?=isset($alias) ? $alias : ''?>" />
            
            <span class="btn btn-small" onclick="$(this).parents('.input').remove();">удалить</span>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label">
            Название поля <br />
            
        </label>
        <div class="controls">
            <input type="text" name="inputs[<?=$i?>][label]" value="<?=isset($input->label) ? $input->label : ''?>" />
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label">
            Подсказка <br />
            
        </label>
        <div class="controls">
            <input type="text" name="inputs[<?=$i?>][help]" value="<?=isset($input->help) ? $input->help : ''?>" />
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label">
            Обязательное поле <br />
            
        </label>
        <div class="controls">
            <input type="checkbox" name="inputs[<?=$i?>][required]" <?=(isset($input->required) AND $input->required) ? 'checked="checked"' : ''?> />
        </div>
    </div>
    
    <?=$this->template->view('inputs/'. $input->type)?>
    
    <hr />
</div>

<script>
    $(function(){
        $(".inputs").sortable({
            items: '.input'
        });
    });
</script>