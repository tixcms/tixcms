<tr id="<?=$item->id?>">
    <td style="width: 20%;">
        <?php if( $item->is_backend() OR $item->is_frontend() ):?>
            <?=URL::anchor(
                ($item->is_backend() ? 'admin/' : '') . $item->url(),
                $item->name()
            )?>
        <?php else:?>
            <?=$item->name()?>
        <?php endif?>
    </td>
    <td style="width: 50%;">
        <?=$item->desc()?>
    </td>
    <td style="text-align: center; width: 5%">
        <?=$item->version()?>
    </td>
    <td style="text-align: center; width: 20%;">
        <?php if( isset($all[$item->url()]) AND $item->version() != $all[$item->url()]->version ):?>
            <?=URL::anchor(
                'admin/modules/addons/update/'. $item->url(),
                'обновить',
                array(
                    'class'=>'btn btn-warning btn-small',
                    'rel'=>'tooltip',
                    'data-title'=>'До версии '. $all[$item->url()]->version()
                )
            )?>
        <?php endif?>
    
        <?php if( $item->url() != 'app' ):?>
            <?=URL::anchor(
                'admin/modules/addons/uninstall/'. $item->url(),
                'удалить',
                array(
                    'class'=>'btn btn-danger btn-small confirm',
                    'data-confirm'=>'При удаление будут удалены все данные этого дополнения. Удалить?'
                )
            )?>
        <?php endif?>
    </td>
</tr>