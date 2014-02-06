<ul class="nav nav-tabs">
    <li<?=$this->action == 'login' ? ' class="active"' : ''?>>
        <?=URL::anchor(
            'users/login', 
            'Вход'
        )?>
    </li>
    <li<?=($this->action == 'register' OR $this->action == 'register_success') ? ' class="active"' : ''?>>
        <?=URL::anchor(
            'users/register', 
            'Регистрация'
        )?>
    </li>
    <li<?=($this->action == 'reset' 
                OR $this->action == 'reset_password' 
                OR $this->action == 'reset_password_success'
            ) 
                ? ' class="active"' : ''?>
    >
        <?=URL::anchor(
            'users/reset', 
            'Восстановление пароля'
        )?>
    </li>
</ul>

<?=$content?>