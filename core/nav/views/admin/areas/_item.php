<div class="page-header">
    <div style="margin-bottom: 5px;" class="pull-right">
        <?=URL::anchor(
            'admin/nav/areas/edit/'. $area->id,
            'редактировать',
            array(
                'class'=>'btn btn-small'
            )
        )?>
        <?=URL::anchor_protected(
            'admin/nav/areas/delete/'. $area->id,
            'удалить',
            array(
                'class'=>'btn btn-small btn-danger delete confirm',
                'data-confirm'=>'Все ссылки в этой области будут удалены. Удалить область ссылок?'
            )
        )?>
    </div>
    <h3><?=$area->name?></h3>
</div>



<?php if( isset($links[$area->alias][0]) ):?>
    <div style="background: white;">
        <ul class="nav-items-list sortable" id="<?=$area->alias?>">
            <?php foreach($links[$area->alias][0] as $link):?>
                <?=$this->template->view('_item', array(
                    'link'=>$link,
                    'parent_id'=>0,
                    'level'=>0
                ))?>
            <?php endforeach?>
        </ul>
    </div>
<?php else:?>
    <p>Нет ссылок</p>
<?php endif?>