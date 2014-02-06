<?php if( $items ):?>
    <p class="pull-right">
        <?=URL::anchor(
            'admin/email/queue/delete_all',
            'Удалить все письма',
            array(
                'class'=>'btn delete-all',
            )
        )?>
    </p>
    <p class="queue-total">
        Всего писем в очереди: <?=$total?>
    </p>

    <div class="table-container">
        <?=Admin\Table::create(array(
            'headings'=>array(
                'От кого',
                'Кому',
                'Тема',
                'Сообщение',
                'Создано',
                'Приоритет'
            ),
            'item_view'=>'queue/_item',
            'items'=>$items,
            'search'=>false
        ))->render()?>
        
        <?=$pager->render()?>
    </div>
<?php else:?>
    <p>Нет писем в очереди</p>
<?php endif?>

<script>
    $(function(){
         $(".delete-all").click(function(){
            if( confirm('Удалить все письма?') ){
                $.get(BASE_URL + 'admin/email/queue/delete_all');
            
                $(".table-container").html('<p>Письма удалены</p>');
                $(".queue-total").remove();
            }
            
            return false;
         });
    });
</script>