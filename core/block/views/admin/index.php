<div class="page-header">
    <div class="header-actions">
        <?php if( ENVIRONMENT == 'development' ):?>
            <?=URL::anchor(
                'admin/block/areas/add', 
                'Создать область блоков', 
                array(
                    'class'=>'btn btn-primary'
                )
            )?>
        <?php endif?>
            
        <?=URL::anchor(
            'admin/block/add', 
            'Добавить блок', 
            array(
                'class'=>'btn btn-primary'
            )
        )?>
    </div> 
</div>

<div class="row-fluid">
    <?=$list->render()?>
</div>