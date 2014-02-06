<div class="control-group field-<?=$field?> <?=$form->error($field) ? 'error' : ''?>">
    <label class="control-label" for="<?=$field?>">
        <?=$label?>
    </label>
    <div class="controls">
    
        <?php if( $options ):?>
        
            <?php $i=0; foreach($options as $area=>$area_options):?>
                <select class="parent_id parent_id_<?=$area?>" <?=(($form->is_insert() AND $i != 0) OR ($form->is_update() AND $form->entity->area_alias != $area)) ? ' style="display: none;"' : ''?> >
                    <option value="0">Корневая</option>
                    <?php foreach($area_options as $option):?>
                        <option
                            value="<?=$option['value']?>"
                            <?=$option['value'] == $value ? 'selected="seleted"' : ''?>
                        >
                            <?=$option['label']?>
                        </option>
                    <?php endforeach?>
                </select>
            <?php $i++; endforeach?>
        
            <input type="hidden" name="parent_id" value="<?=$value?>" />
        <?php else:?>
            <select>
                <option value="0">Корневая</option>
            </select>     
            
            <input type="hidden" value="0" />   
        <?php endif?>
        
        <?php if( $form->show_inline_errors() ):?>
            <span class="error-string error-field-<?=$field?>"><?=$form->error($field)?></span>
        <?php endif?>
         <?=isset($after_input) ? $after_input : ''?>
    </div>
</div>