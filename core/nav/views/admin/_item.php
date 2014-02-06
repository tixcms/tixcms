<li class="item nav-item" data-id="<?=$link->id?>">
    <div class="item-inner">
        <span class="muted" style="padding-left: 5px; display: inline-block;">
           <?=str_repeat('&nbsp;&nbsp;&middot;&nbsp;&nbsp;', $link->level)?>
        </span>
        <span style="text-align: center; width: 10px; cursor: move; padding-right: 5px;" class="sortable-helper">
            <i class="icon-reorder"></i>
        </span>
        <?=URL::anchor(
            'admin/nav/edit/'. $link->id, 
            $link->name
        )?>
        <small>
            <?=URL::anchor($link->url, '/'. ltrim($link->url, '/'), array(
                'class'=>'muted',
                'target'=>'_blank',
                'rel'=>'tooltip',
                'data-title'=>'Адрес ссылки'
            ))?>
        </small>
        <div style="text-align: center; margin-right: 5px;" class="pull-right">
        	<ul class="actions">
        		<li>
                    <?=URL::anchor(
                        'admin/nav/delete/'. $link->id, 
                        'edit', 
                        array(
                            'class'=>'delete ajax-delete confirm',
                            'data-confirm'=>'Удалить ссылку?'
                        )
                    )?>
                </li>
        	</ul>
        </div>
        <div style="text-align: center; margin-right: 15px; width: 60px;" class="pull-right">
            <span 
                class="btn btn-small <?=$link->status ? 'btn-success' : ''?>" 
                style="cursor: pointer;"
                rel="toggle"
                data-mode="<?=$link->status ? 'on' : 'off'?>"
                data-id="<?=$link->id?>"
                data-url="<?=URL::site_url('admin/nav/status/' . $link->id)?>"
                data-on-class="btn-success"
                data-off-class=""
                data-on-text="вкл"
                data-off-text="откл"
            >
                <?=$link->status ? 'вкл' : 'откл'?>
            </span>
        </div>
    </div>

    <?php if( isset($links[$area->alias][$link->id]) ):?>
        <ul class="nav-items-list sortable">
            <?php foreach($links[$area->alias][$link->id] as $sublink):?>
                <?=$this->template->view('_item', array(
                    'link'=>$sublink,
                    'parent_id'=>$sublink->id
                ))?>
            <?php endforeach?>
        </ul>
    <?php endif?>
</li>
