<?php

namespace Users\Forms\Password;

/**
 * Форма смены пароля пользователя
 */
class Change extends \Users\Forms\Password\Reset
{
    function init()
    {
        parent::init();
        
        $this->inputs = array_merge(array(
            'old_password'=>array(
                'type'=>'password',
                'label'=>'Текущий пароль',
                'rules'=>'trim|required|callback_old_password_valid',
                'save'=>FALSE,
                'placeholder'=>'Текущий пароль'
            )
        ), $this->inputs);
    }
    
    function old_password_valid($str)
    {
        if( $this->user->password == $this->auth->dohash($str) )
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('old_password_valid', 'Введен неверный текущий пароль');
            return FALSE;
        }
    }
}