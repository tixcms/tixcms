<tr class="item">
    <td>
        <?php echo URL::anchor(
            'users/admin/groups/edit/'. $item->id, 
            $item->name
        )?>
    </td>
    <td>
        <?=$item->alias?>
    </td>
    <td style="width: 50px; text-align: center;">
    	<ul class="actions">
            <?php if( !$item->default ):?>
        		<li>
                    <?php echo URL::anchor(
                        'users/admin/groups/delete/'. $item->id, 
                        'edit',
                        array(
                            'class'=>'delete ajax-delete confirm',
                            'data-confirm'=>'Если в группе есть пользователи они будут перемещены в группу Пользователи. Удалить группу?'
                        )
                    )?>
                </li>
            <?php endif;?>
    	</ul>
    </td>
</tr>