<tr class="item" data-id="<?=$item_key?>">
    <td>
        <?=URL::anchor(
            'admin/email/templates/form/'. (isset($item['module']) ? $item['module'] : $this->module->url  ) .'/'. $item_key,
            $item['name'],
            array(
                'class'=>'template-edit',
                'data-header'=>$item['name']
            )
        )?>
    </td>
    <td>
        <?=$item['description']?>
    </td>
</tr>