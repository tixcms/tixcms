<?=Block::view('Admin::Nav\Tabs', array(
    'items'=>array(
        array(
            'url'=>'admin/security',
            'label'=>'Общее',
            'active'=>$this->controller == 'security'
        ),
        array(
            'url'=>'admin/security/logs',
            'label'=>'Логи входов',
            'active'=>$this->controller == 'logs'
        )
    )
));?>

<?=$content?>