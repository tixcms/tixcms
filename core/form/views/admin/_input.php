<div class="inputs">
    <?php if( $inputs ):?>
        <?php $i=0; foreach($inputs as $alias=>$input):?>            
            <?=Form\Generated\Input::render_input($alias, $input, $i)?>
        <?php $i++; endforeach?>
    <?php endif?>
</div>

<div class="control-group">
    <label class="control-label">
        Новое поле
    </label>
    <div class="controls get-type">
        <span class="btn btn-small" data-type="text">Строка</span>
        <span class="btn btn-small" data-type="email">Email</span>
        <span class="btn btn-small" data-type="textarea">Текст</span>
        <span class="btn btn-small" data-type="checkbox">Чекбокс</span>
    </div>
</div>

<script>
    $(function(){
        $(".get-type span").click(function(){
            var totalFields = $(".input").length + 1;
            $.get(BASE_URL + 'admin/form/get_type/' + $(this).data('type') + '/' + totalFields, function(data){
                $(".inputs").append(data); 
            });
        });
    });
</script>