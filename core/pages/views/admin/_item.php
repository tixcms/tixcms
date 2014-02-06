<tr class="item" data-level="<?=$item->level?>">
    <td style="text-align: left;">
        <?=str_repeat('&nbsp;&nbsp;<span class="muted">&middot;</span>&nbsp;&nbsp;', $item->level).
        URL::anchor('admin/pages/edit/'. $item->id, $item->title)?>
        
        <?php if( $item->module ):?>
            <small>
                <?=URL::anchor(
                    'admin/'. $item->module,
                    Modules\Helper::name($item->module),
                    array(
                        'class'=>'muted'
                    )
                )?>
            </small>
        <?php endif?>
    </td>
    <td>
        <?=URL::anchor(
            ($item->is_main ? '' : $item->full_url),
            '/'. ($item->is_main ? '' : trim($item->full_url, '/')),
            array(
                'target'=>'_blank'
            )
        )?>
        <i class="icon-external-link"></i>
    </td>
    <td style="width: 100px; text-align: center;">
        <?php if( $item->url != '404' ):?>
            <?php if( $item->is_main ):?>
                <?=URL::anchor_protected(
                    'admin/pages/set_main/'. $item->id,
                    'Да',
                    array(
                        'class'=>'btn btn-small btn-success is-main'
                    )
                )?>
            <?php else:?>
                <?=URL::anchor_protected(
                    'admin/pages/set_main/'. $item->id,
                    'Нет',
                    array(
                        'class'=>'btn btn-small is-main'
                    )
                )?>
            <?php endif?>
        <?php endif?>
    </td>
    <td style="width: 100px; text-align: center;">
        <?php if( $item->level != 0 AND $item->url != '404' ):?>
            <span 
                class="module-menu btn btn-small <?=$item->is_active ? 'btn-success' : ''?>"
                style="cursor: pointer;"
                rel="toggle"
                data-mode="<?=$item->is_active ? 'on' : 'off'?>"
                data-id="<?=$item->id?>"
                data-url="<?=$this->url->site_url_protected('admin/pages/active/' . $item->id)?>"
                data-on-class="btn-success"
                data-off-class=""
                data-on-text="да"
                data-off-text="нет"
            >
                <?=$item->is_active ? 'да' : 'нет'?>
            </span>
        <?php endif?>
    </td>
    <td style="width: 10px; text-align: center;">
        <?php if( $item->level != 0 AND $item->url != '404' ):?>
            <ul class="actions">
                <li>
                    <?=$this->url->anchor_protected(
                        'admin/pages/delete/'. $item->id,
                        'edit',
                        array(
                            'class'=>'delete confirm ajax-delete',
                            'data-confirm'=>'Удалить страницу?'
                        )
                    )?>
                </li>
            </ul>
        <?php endif?>
    </td>
</tr>