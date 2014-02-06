<textarea 
    class="editor-element-code span8"
    name="<?=$editor_name?>[<?=$element_index?>][content]"><?=isset($data['content']) ? $data['content'] : ''?></textarea>
<input type="hidden" name="<?=$editor_name?>[<?=$element_index?>][type]" value="code" />

<script>
    $("[data-index=<?=$element_index?>] textarea").autoResize({
        extraSpace: 10,
        minHeight: 10,
        /*maxHeight: 1000*/
    });
</script>

<style>
    .editor-element-code {
        border: none !important;
        border-radius: 0 !important;
        font-family: Monaco,Menlo,Consolas,"Courier New",monospace;
        font-size: 12px;
        line-height: 14px;
    }
</style>

