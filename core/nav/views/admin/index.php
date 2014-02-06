<div class="page-header">
    <div class="header-actions">
        <?=URL::anchor(
            'admin/nav/areas/add',
            'Добавить область',
            array(
                'class'=>'btn btn-primary'
            )
        )?>
        <?=URL::anchor(
            'admin/nav/add',
            'Добавить ссылку',
            array(
                'class'=>'btn btn-primary'
            )
        )?>
    </div>
</div>

<?php if( $areas ):?>
    <?=Block::view('Nav::Nav\Areas')?>
<?php else:?>
    <p>Нет областей ссылок. <?=URL::anchor('admin/nav/areas/add', 'Создать')?></p>
<?php endif?>