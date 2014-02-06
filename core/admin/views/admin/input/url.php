<div class="control-group field-<?=$field?> <?=$form->error($field) ? 'error' : ''?>">
    <label class="control-label" for="<?=$field?>">
        <?php if( $help ):?>
            <span class="input-help" data-content="<?=$help?>"><?=$label?></span>
        <?php else:?>
            <?=$label?>
        <?php endif?>
    </label>
    <div class="controls">
        <div <?=$url_prepend !== false ? ' class="input-prepend"' : ''?>>
            <?php if( $url_prepend !== false ):?>
                <span class="add-on"><?=URL::base_url() . $url_prepend?></span>
            <?php endif?>
            
            <input
                id="<?=$field?>"
                type="text"
                name="<?=$field?>" 
                value="<?=$value?>"
                <?=HTML\Tag::parse_attributes(array_merge($attrs, array('style'=>$url_prepend !== false ? 'width: 150%' : '')))?> 
                <?=isset($placeholder) ? 'placeholder="'. $placeholder .'"' : ''?>
            />
            
            <?php if( $form->show_inline_errors() ):?>
                <span class="error-string error-field-<?=$field?>"><?=$form->error($field)?></span>
            <?php endif?>
            <?=isset($after_input) ? $after_input : ''?>
        </div>
    </div>
</div>

<?php if( !isset($attrs['disabled']) ):?>
    <script>
        var translitChars = new Array;
        var auto = true;
    
        <?php foreach($form->di->string->translitCharacters as $key=>$value):?>
            translitChars['<?=$key?>'] = '<?=$value?>';
        <?php endforeach?>
    
        $(function(){
            
            if( $(".field-<?=$field?> input[type=text]").val() != ''){
                auto = false;
            }
            
            $(".field-<?=$source_input?> input[type=text]").keyup(function(){
                if( auto ){
                    var string = $(this).val();            
                    var translitString = translit(string);
                    
                    $(".field-<?=$field?> input[type=text]").val(translitString);
                }
            });
            
            $(".field-<?=$field?> input[type=text]").keyup(function(){
                auto = $(this).val() == '';
            });
        });
        
        function translit(string){
            var result = '';
            
            for(var i = 0; i<string.length; i++){
                var stringChar = string.substr(i, 1);
                
                if( translitChars[stringChar] ){
                    result += translitChars[stringChar];
                } else {
                    result += stringChar;
                }
            }    
            
            result = result.replace(/[^A-Za-z0-9_\-]/g, '');
            
            return result.toLowerCase();
        }
    </script>
<?php endif?>