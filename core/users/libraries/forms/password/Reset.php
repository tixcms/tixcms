<?php

namespace Users\Forms\Password;

/**
 * Форма смена пароля при восстановлении пароля
 */
class Reset extends \App\Form
{
    public $actions_view = 'forms/actions/reset_password';
    
    public $inputs = array(
        'password'=>array(
            'type'=>'password',
            'label'=>'Пароль',
            'placeholder'=>'Пароль',
            'rules'=>'trim|required',
            'value'=>''
        ),
        'password_retype'=>array(
            'type'=>'password',
            'label'=>'Повтор пароля',
            'placeholder'=>'Повтор пароля',
            'rules'=>'trim|required|matches[password]',
            'save'=>FALSE
        ),
    );
    
    function before_save()
    {
        // обновляем данные юзера
        $this->set('password', $this->auth->dohash($this->get('password')));
        $this->set('reset_token', '');
    }
}