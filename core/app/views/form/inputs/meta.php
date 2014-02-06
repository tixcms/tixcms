<div>
    <div class="control-group">
        <div class="controls meta-fields-toggle">
            <span>Дополнительные поля для указания заголовка страницы, описания и ключевых слов</span>
        </div>
    </div>
    
    <div class="meta-controls" <?=$slide ? '' : 'style="display: none;"'?>>
        <?=$this->template->view($form->inputs_folder . 'text', array(
            'label'=>$labels['title'],
            'field'=>$fields['title'],
            'value'=>$values['title'],
            'placeholder'=>$placeholders['title']
        ))?>
        
        <?=$this->template->view($form->inputs_folder . 'text', array(
            'label'=>$labels['description'],
            'field'=>$fields['description'],
            'value'=>$values['description'],
            'placeholder'=>$placeholders['description']
        ))?>
        
        <?=$this->template->view($form->inputs_folder . 'text', array(
            'label'=>$labels['keywords'],
            'field'=>$fields['keywords'],
            'value'=>$values['keywords'],
            'placeholder'=>$placeholders['keywords']
        ))?>
    </div>
</div>
<input type="hidden" name="meta-slide" <?=$slide ? 'value="true"' : ''?> />

<style>
    .meta-fields-toggle span {
        color: #08C;
        border-bottom: 1px dashed;
        cursor: pointer;
    }
</style>

<script>
    $(function(){
        $(".meta-fields-toggle span").click(function(){
            if( $(".meta-controls").is(':visible') ){
                $(".meta-controls").slideUp();
                $("[name=meta-slide]").val('');
            } else {
                $(".meta-controls").slideDown();
                $("[name=meta-slide]").val(true);
            }
        });
    });
</script>