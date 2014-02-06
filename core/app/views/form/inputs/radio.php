<div>
    <?=$label?>:
</div>

<div class="form-group">
    
    <div class="controls">
    
        <?php foreach($options as $option_key=>$option_value):?>
    
            <label>
                <input 
                    type="radio" 
                    name="<?=$field?>"
                    value="<?=$option_key?>"
                    <?=(isset($value) AND $value) ? 'checked="checked"' : ''?>
                    <?=HTML\Tag::parse_attributes($attrs)?> 
                />
                
                <?=$option_value?>
            </label>
            
        <?php endforeach?>
    </div>
    
</div>