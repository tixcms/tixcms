<ul class="nav nav-tabs">
    <li<?=$this->action == 'index' ? ' class="active"' : ''?>>
        <?=URL::anchor(
            'users/profile/edit',
            'Данные'
        )?>
    </li>
    <li<?=$this->action == 'password' ? ' class="active"' : ''?>>
        <?=URL::anchor(
            'users/profile/edit/password',
            'Смена пароля'
        )?>
    </li>
</ul>

<?=$this->di->alert->render()?>

<?=$content?>