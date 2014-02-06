<div class="control-group">
    <label class="control-label" for="">
        <?=$label?>:
    </label>
    <div class="controls controls-row">        
        <select name="<?=$field?>_day" style="width: 80px;">
            <?=HTML\Tag::options($days, $values['day'])?>
        </select>
        
        <select name="<?=$field?>_month" style="width: 100px;">
            <?=HTML\Tag::options($months, $values['month'])?>
        </select>
        
        <select name="<?=$field?>_year" style="width: 100px;">
            <?=HTML\Tag::options($years, $values['year'])?>
        </select>
        
        <?php if( $show_time ):?>
            <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            
            <select name="<?=$field?>_hours" style="width: 60px;">
                <?=HTML\Tag::options(range(0, 23), $values['hours'])?>
            </select>
            :
            <select name="<?=$field?>_minutes" style="width: 60px;">
                <?=HTML\Tag::options(range(0, 59), $values['minutes'])?>
            </select>
        <?php endif?>
        
        <span class="btn btn-small set-now" data-field="<?=$field?>">сейчас</span>
    </div>
</div>

<script>
    $(function(){
        $(".set-now").click(function(){
            var date = new Date();
            var field = $(this).data('field');
            
            $("[name=" + field + "_day] option").removeAttr('selected');
            $("[name=" + field + "_day] option[value=" + date.getDate() + "]").attr('selected', 'selected');
            
            $("[name=" + field + "_month] option").removeAttr('selected');
            $("[name=" + field + "_month] option[value=" + (date.getMonth() + 1) + "]").attr('selected', 'selected');
            
            $("[name=" + field + "_year] option").removeAttr('selected');
            $("[name=" + field + "_year] option[value=" + date.getFullYear() + "]").attr('selected', 'selected');
            
            $("[name=" + field + "_hours] option").removeAttr('selected');
            $("[name=" + field + "_hours] option[value=" + date.getHours() + "]").attr('selected', 'selected');
            
            $("[name=" + field + "_minutes] option").removeAttr('selected');
            $("[name=" + field + "_minutes] option[value=" + date.getMinutes() + "]").attr('selected', 'selected');
        });
    });
</script>