<tr class="item" data-level="<?=$item->level?>">
    <td style="text-align: left;">
        <?=str_repeat('&nbsp;&nbsp;<span class="muted">&middot;</span>&nbsp;&nbsp;', $item->level - 1). 
        URL::anchor('admin/'. $this->module->url .'/categories/edit/'. $item->id, $item->title)?>
    </td>
    <td style="text-align: center; width: 50px;">
        <?=$item->items?>
    </td>
    <td style="width: 100px; text-align: center;">
        <span 
            class="module-menu btn btn-small <?=$item->is_active ? 'btn-success' : ''?>"
            style="cursor: pointer;"
            rel="toggle"
            data-mode="<?=$item->is_active ? 'on' : 'off'?>"
            data-id="<?=$item->id?>"
            data-url="<?=URL::site_url('admin/'. $this->module->url .'/categories/active/' . $item->id)?>"
            data-on-class="btn-success"
            data-off-class=""
            data-on-text="да"
            data-off-text="нет"
        >
            <?=$item->is_active ? 'да' : 'нет'?>
        </span>
    </td>
    <td style="width: 10px; text-align: center;">
    	<ul class="actions">
    		<li>
                <?php echo URL::anchor(
                    'admin/'. $this->module->url .'/categories/delete/'. $item->id, 
                    'edit',
                    array(
                        'class'=>'delete confirm ajax-delete',
                        'data-confirm'=>'Удалить категорию?'
                    )
                )?>
            </li>
    	</ul>
    </td>
</tr>