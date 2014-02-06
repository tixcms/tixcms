<div class="control-group">
    <label class="control-label" for="">
        <?=$label?>
    </label>
    <div class="controls">
        <select name="type" class="type">
            <?=HTML\Tag::options(Nav\Helper::type_options(), $value)?>
        </select>
    </div>
</div>