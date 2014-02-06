<input type="text" class="editor-element-header span8" name="<?=$editor_name?>[<?=$element_index?>][content]" value="<?=isset($data['content']) ? $data['content'] : ''?>" />

<span class="btn btn-small btn-primary">H<?=(isset($data['header']) AND $data['header']) ? $data['header'] : 1?></span>

<div class="btn-group closed hide">
    <span class="btn btn-small" data-header="1">H1</span>
    <span class="btn btn-small" data-header="2">H2</span>
    <span class="btn btn-small" data-header="3">H3</span>
    <span class="btn btn-small" data-header="4">H4</span>
    <span class="btn btn-small" data-header="5">H5</span>
    <span class="btn btn-small" data-header="6">H6</span>
</div>    

<input type="hidden" name="<?=$editor_name?>[<?=$element_index?>][type]" value="header" />
<input type="hidden" class="header-input" name="<?=$editor_name?>[<?=$element_index?>][header]" value="<?=(isset($data['header']) AND $data['header']) ? $data['header'] : 1?>" />



<style>
    .editor-element-header {
        border: none !important;
        border-radius: 0 !important;
        font-size: 20px !important;
        font-weight: bold;
    }
</style>

<script>
    $(document).on('click', "[data-index=<?=$element_index?>] .btn-primary", function(){
        $("[data-index=<?=$element_index?>] .btn-group").removeClass('hide');
    });
    
    $(document).on('click', "[data-index=<?=$element_index?>] .btn-group .btn", function(){
        var header = $(this).data('header');
        
        $("[data-index=<?=$element_index?>] .header-input").val(header);
        
        $("[data-index=<?=$element_index?>] .btn-primary").html($(this).html());
        $("[data-index=<?=$element_index?>] .btn-group").addClass('hide');
    });
</script>


