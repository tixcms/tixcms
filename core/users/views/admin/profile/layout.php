<?=Block::get('Admin::Nav\Tabs', array(
    'items'=>array_merge(array(
        array(
            'label'=>'&larr; К списку',
            'url'=>'admin/users',
            'active'=>false
        ),
        array(
            'label'=>'Профиль - <strong>'. $user->login .'</strong>',
            'url'=>'admin/users/profile/view/'. $user->id,
            'active'=>isset($action_view)
        ),
        array(
            'label'=>'Редактирование',
            'url'=>'admin/users/edit/'. $user->id,
            'active'=>($this->action == 'edit' AND $this->module->url == 'users' AND $this->controller == 'users')
        ),
    ), $tabs)
))?>

<?=$content?>