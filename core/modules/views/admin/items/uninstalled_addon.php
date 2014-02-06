<tr>
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
    <td style="width: 60%;">
        <?=$item->description()?>
    </td>
    <td style="text-align: center; width: 5%">
        <?=$item->version()?>
    </td>
    <td style="text-align: center; width: 20%;">
        <?=URL::anchor(
            'admin/modules/addons/install/'. $item->url(),
            'установить',
            array(
                'class'=>'btn btn-success btn-small'
            )
        )?>
    </td>
</tr>