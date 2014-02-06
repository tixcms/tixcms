<?=Admin\Table::create(array(
    'item_view'=>'profile/_item',
    'items'=>$data,
    'bordered'=>false,
    'search'=>false
))->render()?>