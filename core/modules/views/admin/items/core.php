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
    <td>
        <?=$item->desc()?>
    </td>
</tr>