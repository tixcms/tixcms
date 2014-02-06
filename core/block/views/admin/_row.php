<tr class="item" data-id="<?=$item->id?>">
    <td style="text-align: center; width: 10px;cursor: move;" class="sortable-helper">
        <i class="fa fa-arrows-v"></i>
    </td>
    <td>
        <?=$this->url->anchor(
            'admin/block/edit/'. $item->id,
            $item->title
        )?>
        <small class="muted">{{block:inst id="<?=$item->id?>"}}</small>
    </td>
    <td style="width: 40px; text-align: center;">
        <span 
            class="btn btn-small <?=$item->active ? 'btn-success' : ''?>" 
            style="cursor: pointer;"
            rel="toggle"
            data-mode="<?=$item->active ? 'on' : 'off'?>"
            data-id="<?=$item->id?>"
            data-url="<?=$this->url->site_url('admin/block/active/' . $item->id)?>"
            data-on-class="btn-success"
            data-off-class=""
            data-on-text="вкл"
            data-off-text="откл"
        >
            <?=$item->active ? 'вкл' : 'откл'?>
        </span>
    </td>
    <td style="width: 50px; text-align: center;">
    	<ul class="actions">
    		<li>
                <?=$this->url->anchor(
                    'admin/block/delete/'. $item->id, 
                    'edit', 
                    array(
                        'class'=>'delete ajax-delete confirm',
                        'data-confirm'=>'Удалить блок?'
                    )
                )?>
            </li>
    	</ul>
    </td>
</tr>