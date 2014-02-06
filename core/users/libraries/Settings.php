<?php

namespace Users;

class Settings extends \Settings\Form
{    
    function inputs()
    {
        return array(
            'users_registration'=>array(
                'type'=>'select',
                'label'=>'Регистрация пользователей',
                'options'=>array(
                    1=>'Открыта',
                    0=>'Закрыта'
                ),
                'default'=>1
            ),
            'users_must_use_login'=>new \Form\Input\Checkbox(array(
                'label'=>'Требовать логин для регистрации',
                'default'=>true
            ))
        );
    }
}