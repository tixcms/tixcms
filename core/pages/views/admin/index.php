<div class="page-header">
    <div class="header-actions">
        <?=URL::anchor(
            'admin/pages/add',
            lang('page:create'),
            array(
                'class'=>'btn btn-primary'
            )
        )?>
        <?php if( $table->total ):?>
            <?=URL::anchor(
                'admin/pages/reorder',
                lang('page:change-order'),
                array(
                    'class'=>'btn'
                )
            )?>
        <?php else:?>
            <a class="btn" disabled="disabled">Изменить порядок страниц</a>
        <?php endif?>
    </div>
</div>

<?=$table->render()?>