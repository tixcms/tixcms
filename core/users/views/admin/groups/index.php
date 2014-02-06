<div class="page-header">
    <div class="header-actions">
        <?=URL::anchor(
            'admin/users/groups/add',
            'Создать группу',
            array(
                'class'=>'btn btn-primary'
            )
        )?>
    </div>
</div>

<?=Admin\Table::create(array(
    'items'=>$groups,
    'item_view'=>'groups/_item',
    'search'=>false,
    'headings'=>array(
        'Название',
        'Идентификатор',
        'Действия'
    )
))->render()?>