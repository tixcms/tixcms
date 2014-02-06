<tr class="item">
    <td>
        <?=$this->url->anchor(
            'admin/form/edit/'. $item->id,
            $item->name
        )?>
    </td>
    <td>
        <code>{{form:render alias="<?=$item->alias?>"}}</code>
    </td>
    <td class="actions-td">
    	<ul class="actions">
    		<li>
                <?=$this->url->anchor_protected(
                    'admin/form/delete/'. $item->id, 
                    '',
                    array(
                        'class'=>'delete ajax-delete confirm',
                        'data-confirm'=>'Удалить новость?'
                    )
                )?>
            </li>
    	</ul>
    </td>
</tr>