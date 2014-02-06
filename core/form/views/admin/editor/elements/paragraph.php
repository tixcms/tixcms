<textarea class="editor-element-paragraph span8" name="<?=$editor_name?>[<?=$element_index?>][content]"><?=isset($data['content']) ? $data['content'] : ''?></textarea>
<input type="hidden" name="<?=$editor_name?>[<?=$element_index?>][type]" value="paragraph" />

<style>
    .editor-element-paragraph {
        border: none !important;
        border-radius: 0 !important;
    }
</style>