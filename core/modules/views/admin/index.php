<?php if( $uninstalled_addons ):?>

    <div class="page-header">
        <h3>Неустановленные дополнения</h3>
    </div>

    <?=Admin\Table::create(array(
        'item_view'=>'items/uninstalled_addon',
        'items'=>$uninstalled_addons,
        'headings'=>array(
            'Название',
            'Описание',
            'Версия',
            'Действия'
        ),
        'search'=>false,
        'per_page'=>false,
        'show_total_counter'=>false
    ))->render()?>
<?php endif?>

<?php if( $installed_addons ):?>
    <div class="page-header">
        <h3>Установленные дополнения</h3>
    </div>

    <?=Admin\Table::create(array(
        'item_view'=>'items/installed_addon',
        'items'=>$installed_addons,
        'headings'=>array(
            'Название',
            'Описание',
            'Версия',
            'Действия'
        ),
        'search'=>false,
        'per_page'=>false,
        'show_total_counter'=>false
    ))->render()?>
<?php endif?>


<div class="page-header">
    <div class="header-actions pull-right">
    </div>
    <h3>Ядро 
        <small>
            версия <?=$core_version?>
            <?php if( $core_version != $new_core_version ):?>
                <?=URL::anchor(
                    'admin/modules/core/update',
                    'обновить до версии '. $new_core_version,
                    array(
                        'class'=>'btn btn-warning btn-small'
                    )
                )?>
            <?php endif?>
        </small>
    </h3>
</div>

<?=Admin\Table::create(array(
    'item_view'=>'items/core',
    'items'=>$core_modules,
    'headings'=>array(
        'Название',
        'Описание'
    ),
    'search'=>false,
    'per_page'=>false,
    'show_total_counter'=>false
))->render()?>

<style>
    .module-enable {cursor: pointer;}
</style>