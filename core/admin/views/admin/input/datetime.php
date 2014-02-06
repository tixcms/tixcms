<div class="control-group">
    <label class="control-label" for="">
        <?php if( $help ):?>
            <span class="input-help" data-content="<?=$help?>"><?=$label?></span>
        <?php else:?>
            <?=$label?>
        <?php endif?>
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
        
        <?php if( $show_now_button ):?>
            <span class="btn btn-small set-now" data-field="<?=$field?>">
                <?php if( $show_time ):?>
                    <?=lang('сейчас')?>
                <?php else:?>
                    <?=lang('сегодня')?>
                <?php endif?>            
            </span>
        <?php endif?>
    </div>
</div>

<?php if( $show_now_button ):?>
    <script>
        $(function(){
            $(".set-now").click(function(){
                var date = new Date();
                var field = $(this).data('field');
                
                $("[name=" + field + "_day]").val(date.getDate());                
                $("[name=" + field + "_month]").val(date.getMonth() + 1);                
                $("[name=" + field + "_year]").val(date.getFullYear());
                
                <?php if( $show_time ):?>
                    $("[name=" + field + "_hours]").val(date.getHours());                    
                    $("[name=" + field + "_minutes]").val(date.getMinutes());
                <?php endif?>
            });
        });
    </script>
<?php endif?>