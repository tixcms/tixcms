<div class="page-header">
    <h3>Проверка прав на файлы и папки</h3>
</div>

<?=HTML\Table::create(array(
    'attrs'=>array(
        'class'=>'table table-bordered'  
    ),
    'headings'=>array(
        'Путь',
        'Значение'
    ),
    'items'=>$items,
    'item_view'=>'_item'
))->render()?>

<form class="form">
    <div class="form-actions">
        <?=URL::anchor(
            $error ? 'install/step1' : 'install/step2',
            $error ? 'Обновить' : 'Далее',
            array(
                'class'=>'btn btn-primary',
            )
        )?>
    </div>
</form>