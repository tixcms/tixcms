<div class="control-group">
    <?php if($vars):?>
        <p>
        Переменные:
        <?php foreach($vars as $key=>$var):?>
            <span class="label" onclick="insertAtCaret('template', $(this).data('alias'));" data-alias="{{<?=$key?>}}" style="cursor: pointer;">
                <?=$var?>
            </span>&nbsp;
        <?php endforeach?> 
        </p>
    <?php endif?>

    <?=HTML\Tag::textarea($field, $value, array(
        'style'=>'width: 98%;',
        'id'=>'template'
    ))?>
</div>

<script>
    $(function(){
        $("textarea").autoResize(); 
    });
</script>