<div class="control-group field-<?=$field?> <?=$form->error($field) ? 'error' : ''?>">
    <label class="control-label" for="">
        <?php if( $help ):?>
            <span class="input-help" data-content="<?=$help?>"><?=$label?></span>
        <?php else:?>
            <?=$label?>
        <?php endif?>
    </label>
    <div class="controls">
    
        <div class="editor-actions btn-group" style="margin-bottom: 5px;"> 
            <span class="btn btn-small editor-element-button" data-element-type="paragraph" title="Параграф"><strong>P</strong></span>
            <span class="btn btn-small editor-element-button" data-element-type="header" title="Заголовок">H</span>
            <span class="btn btn-small editor-element-button" data-element-type="image" title="Изображение"><i class="icon-picture"></i></span>
            <span class="btn btn-small editor-element-button" data-element-type="code" title="Код"><i class="icon-code"></i></span>
            <span class="btn btn-small editor-element-button" data-element-type="blockquote" title="Цитата"><i class="icon-quote-right"></i></span>
        </div>
        
        <?=isset($after_input) ? $after_input : ''?>
        <?php if( $form->show_inline_errors() ):?>
            <span class="error-string error-field-<?=$field?>"><?=$form->error($field)?></span>
        <?php endif?>
    </div>
</div>

<div class="editor-content" data-name="<?=$field?>">

    <?php if( $elements ):?>
    
        <?php $i=1; foreach($elements as $element):?>
        
            <div class="control-group editor-element" data-index="<?=$i?>">
                <label class="control-label">
                    <div class="editor-element-actions pull-left">
                        <span class="editor-element-delete btn btn-small" title="Удалить"><i class="icon-trash"></i></span>
                    </div>
                    
                    <div class="pull-right">
                        <span class="js-editor-element-reorder-handle" title="Переместить"><i class="icon-reorder"></i></span>
                    </div>
                </label>
                <div class="controls">  
                    <?=$generator->set_element(is_array($element) 
                            ? $element['type'] 
                            : $element->type, $i, $field, (array)$element)
                        ->run()?>
                </div>
            </div>
        
        <?php $i++; endforeach?>
    
    <?php endif?>
    
    <div class="control-group editor-element editor-element-template hide">
        <label class="control-label">
            <div class="editor-element-actions pull-left">
                <span class="editor-element-delete btn btn-small" title="Удалить"><i class="icon-trash"></i></span>
            </div>
            
            <div class="pull-right">
                <span class="js-editor-element-reorder-handle" title="Переместить"><i class="icon-reorder"></i></span>
            </div>
        </label>
        <div class="controls">
        </div>
    </div>
</div>

<script>
    var elementIndex = <?=$elements_count?>;
    var editorName = '<?=$field?>';

    $(function(){
        $(".editor-element-button").click(function(){
            var elementType = $(this).data('element-type');
            var currentElementIndex = elementIndex;
            $.get(BASE_URL + 'admin/form/editor/get_element/' + elementType + '/' + currentElementIndex + '/' + editorName, function(html){
                var editorElement = $(".editor-element-template").clone();
                editorElement.removeClass('editor-element-template').show();
                editorElement.attr('data-index', currentElementIndex);
                editorElement.find('.controls').html(html);
                $(".editor-content").append(editorElement);
            });
            
            elementIndex++;
        });
        
        $("[data-name=<?=$field?>].editor-content").sortable({
            handle: '.js-editor-element-reorder-handle'
        });
        
        $("[data-name=<?=$field?>] .js-editor-element-reorder-handle, [data-name=<?=$field?>] .editor-element-delete").hover(function(){
            $(this).css('opacity', 1);
        }, function(){
            $(this).css('opacity', 0.2);
        });
        
        $(document).on('click', ".editor-element-delete", function(){
            $(this).parents('.editor-element').remove();
        });
    });
</script>

<style>
    .js-editor-element-reorder-handle {
        cursor: pointer;
    }
    .editor-element-delete, .js-editor-element-reorder-handle {
        opacity: 0.2;
    }
    .editor-element {
        margin-bottom: 5px !important;
    }
    .editor-content {
        //background: white;
    }
</style>