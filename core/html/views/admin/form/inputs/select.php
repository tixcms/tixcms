<div class="control-group">
    <label class="control-label" for="">
        <?=$label?>:
    </label>
    <div class="controls">
        <select name="<?=$field?>" <?=HTML\Tag::parse_attributes($attrs)?>>
            <?php echo HTML\Tag::options($options, $value)?>
        </select>
    </div>
</div>