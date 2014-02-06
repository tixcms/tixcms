<textarea class="editor-element-blockquote span8" name="<?=$editor_name?>[<?=$element_index?>][content]"><?=isset($data['content']) ? $data['content'] : ''?></textarea>
<input type="hidden" name="<?=$editor_name?>[<?=$element_index?>][type]" value="blockquote" />

<script>
    $("[data-index=<?=$element_index?>] textarea").autoResize({
        extraSpace: 10,
        minHeight: 10,
        maxHeight: 1000
    });
</script>

<style>
    .editor-element-blockquote {
        border: none !important;
        border-radius: 0 !important;
        padding-left: 20px;
    }
</style>