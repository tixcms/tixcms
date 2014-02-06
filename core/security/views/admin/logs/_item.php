<tr>
    <td style="text-align: center; width: 10%;">
        <?php if( $item->type == Logs_m::TYPE_FAIL_LOGIN ):?>
            <span class="badge badge-important" title="неверно введены данные">ошибка</span>
        <?php elseif( $item->type == Logs_m::TYPE_LOGIN ):?>
            <span class="badge badge-success">вход</span>
        <?php else:?>
            <span class="badge">выход</span>
        <?php endif?>
    </td>
    <td>
        <?php if( $item->user_id ):?>
            <?=URL::anchor(
                'admin/users/edit/'. $item->user->id,
                $item->user->login,
                array(
                    'target'=>'_blank'
                )
            )?>
        <?php else:?>
            <?=$item->login ? $item->login : '&nbsp;'?>
        <?php endif?>
    </td>
    <td>
        <?=Helpers\Date::nice($item->created_on)?>
    </td>
    <td>
        <?=$item->ip?>
        <?=URL::anchor(
            str_replace('{ip}', $item->ip, $this->settings->security_ip_checker),
            '<i class="icon-external-link"></i>',
            array(
                'target'=>'_blank',
                'title'=>'Просмотреть информацию об IP адресе'
            )
        )?>
    </td>
    <td>
        <span title="<?=$item->user_agent?>">
            <?=Helpers\Text::character_limiter($item->user_agent, 30, '...')?>
        </span>
    </td>
    <td style="text-align: center;">
        <?=$item->backend ? '<span class="badge badge-important">да</span>' : '<span class="badge">нет</span>'?>
    </td>
</tr>