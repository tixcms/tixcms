<div class="page-header">
    <div class="header-actions">
        <?=URL::anchor(
            'admin/'. $this->module->url .'/categories',
            'Вернуться к списку категорий',
            array(
                'class'=>'btn'
            )
        )?>
    </div>
</div>

<?php if( $categories ):?>
    <?=Categories\Tree::create($categories, FALSE, array(
        'listOpenTag'=>'<ol class="list sortable">',
        'listCloseTag'=>'</ol>',
        'elementOpenTag'=>'<li class="element" data-id="{id}" id="list_{id}">',
        'elementCloseTag'=>'</li>',
        'content'=>'<div>{title}</div>',
        'indent'=>'&nbsp;&nbsp;<span class="muted">·</span>&nbsp;&nbsp;'
    ))->render()?>
<?php else:?>
    <p>Нет категорий</p>
<?php endif?>