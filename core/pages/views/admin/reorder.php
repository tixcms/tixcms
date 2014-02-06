<div class="page-header">
    <div class="header-actions">
        <?=URL::anchor(
            'admin/pages',
            'Вернуться к списку страниц',
            array(
                'class'=>'btn'
            )
        )?>
    </div>
</div>

<?php if( $items ):?>
    <?=Categories\Tree::create($items, FALSE, array(
        'listOpenTag'=>'<ol class="list sortable">',
        'listCloseTag'=>'</ol>',
        'elementOpenTag'=>'<li class="element" data-id="{id}" id="list_{id}">',
        'elementCloseTag'=>'</li>',
        'content'=>'<div>{title}</div>',
        'indent'=>'&nbsp;&nbsp;&nbsp;&nbsp;'
    ))->render()?>
<?php endif?>