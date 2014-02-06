<div class="row-fluid">

    <div class="editor-element-image-container span8" style="text-align: <?=(isset($data['align'])) ? $data['align'] : 'center'?>; margin-top: 5px; background: white; padding: 5px 0px;"><img src="<?=isset($data['src']) ? $data['src'] : ''?>" /></div>

    <div class="span4" style="margin-left: 2px;">
        <input type="text" class="editor-element-image span4" name="<?=$editor_name?>[<?=$element_index?>][src]" value="<?=isset($data['src']) ? $data['src'] : ''?>" placeholder="Ссылка на изображение" />
        <br />
        <span class="btn-group editor-element-image-align-<?=$element_index?>">
            <span class="btn <?=(isset($data['align']) AND $data['align'] == 'left') ? 'btn-primary' : ''?> btn-small" data-align="left"><i class="icon-align-left"></i></span>
            <span class="btn <?=(!isset($data['align']) OR $data['align'] == 'center') ? 'btn-primary' : ''?> btn-small" data-align="center"><i class="icon-align-center"></i></span>
            <span class="btn <?=(isset($data['align']) AND $data['align'] == 'right') ? 'btn-primary' : ''?> btn-small" data-align="right"><i class="icon-align-right"></i></span>
        </span>
    </div>

</div>

<input type="hidden" name="<?=$editor_name?>[<?=$element_index?>][type]" value="image" />
<input type="hidden" class="editor-element-image-align-input-<?=$element_index?>" name="<?=$editor_name?>[<?=$element_index?>][align]" value="<?=(isset($data['align'])) ? $data['align'] : 'center'?>" />

<script>
    $(function(){
        $("[data-index=<?=$element_index?>] .editor-element-image").keyup(function(){
            var imageSrc = $(this).val();
            
            $("[data-index=<?=$element_index?>] .editor-element-image-container img").attr('src', imageSrc);
        });
        
        $(".editor-element-image-align-<?=$element_index?> .btn").click(function(){
             $(".editor-element-image-align-<?=$element_index?> .btn").removeClass('btn-primary');
             $(this).addClass('btn-primary');
             
             var align = $(this).data('align');
             $("[data-index=<?=$element_index?>] .editor-element-image-container").css('text-align', align);
             $(".editor-element-image-align-input-<?=$element_index?>").val(align);
        });
    });
</script>
<style>
    .editor-element-image-align-<?=$element_index?> .btn-primary i {
        color: white;
    }
</style>