<tr class="item linkable" data-url="admin/users/profile/view/<?=$item->id?>" data-id="<?=$item->id?>">
    <td style="width: 10px;">
        <input type="checkbox" name="ids[<?=$item->id?>]" class="check" data-id="<?=$item->id?>" />
    </td>

    <?php if( $this->settings->users_must_use_login ):?>
        <td>
            <?=$this->url->anchor(
                'admin/users/profile/view/'. $item->id,
                $item->login ? $item->login : '<strong><i>Имя не указано</i></strong>'
            )?>            
        </td>
    <?php endif?>
    <td>
        <?=$item->email?>
    </td>
    <td>
        <?=Users\Groups::label($item->group_alias)?>
    </td>
    <td style="text-align: center; width: 150px;">
        <?php if( !in_array($item->group_alias, array('admins', 'guests')) ):?>
            <?php echo \Helpers\Date::nice($item->register_date)?>
        <?php endif?>
    </td>
    <td style="text-align: center; width: 60px;">
        <?=$item->is_active == \Users_m::STATUS_ACTIVATED
            ? '<span class="label label-success" rel="tooltip" data-title="Пользователь прошел активацию">&nbsp;&nbsp;&nbsp;&nbsp;</span>' 
            : '<span class="label" rel="tooltip" data-title="Пользователь не прошел активацию">&nbsp;&nbsp;&nbsp;&nbsp;</span>'?>
    </td>
    <td style="text-align: center; width: 10px;">
        <?php if( $item->id != 0 ):?>
            <ul class="actions">                
                <?php if( $item->id != $this->user->id ):?>
                    <li>
                        <?=$this->url->anchor_protected(
                            'admin/users/delete/'. $item->id, 
                            'Удалить', 
                            array(
                                'class'=>'delete confirm ajax-delete',
                                'data-confirm'=>'Удалить пользователя '. $item->login .'?'
                            )
                        )?>
                    </li>
                <?php endif?>
            </ul>
        <?php endif;?>
    </td>
</tr>