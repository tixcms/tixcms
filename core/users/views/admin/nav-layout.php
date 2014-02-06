<ul class="nav nav-tabs">
    <li<?=($this->controller == 'users' OR $this->controller == 'profile') ? ' class="active"' : ''?>>
        <?=URL::anchor(
            'admin/users',
            'Пользователи'
        )?>
    </li>
    <li<?=$this->controller == 'groups' ? ' class="active"' : ''?>>
        <?=URL::anchor(
            'admin/users/groups',
            'Группы'
        )?>
    </li>
    <li<?=$this->controller == 'permissions' ? ' class="active"' : ''?>>
        <?=URL::anchor(
            'admin/users/permissions',
            'Права'
        )?>
    </li>
</ul>

<?=$content?>