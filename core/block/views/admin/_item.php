<li class="cursor <?=($item['module'] == $block['module'] AND $item['alias'] == $block['alias'] ) ? ' active' : ''?>">
    <?=$this->url->anchor(
        'admin/block/add/'. $item['module'] .'/'. $item['alias'],
        $item['name'],
        array(
            'data-title'=>'Описание',
            'data-content'=>$item['description']
        )
    )?>
</li>