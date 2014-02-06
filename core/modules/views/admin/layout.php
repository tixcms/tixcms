<?=Block::view('Admin::Nav\Tabs', array(
    'items'=>array(
        array(
            'url'=>'admin/modules',
            'label'=>'Управление модулями',
            'active'=>$this->controller == 'modules'
        ),
        array(
            'url'=>'admin/modules/groups',
            'label'=>'Группы',
            'active'=>$this->controller == 'groups' AND $this->action != 'modules_sort'
        ),
        array(
            'url'=>'admin/modules/groups/modules_sort',
            'label'=>'Сортировка модулей',
            'active'=>$this->controller == 'groups' AND $this->action == 'modules_sort'
        ),
    )
))?>

<?=$content?>