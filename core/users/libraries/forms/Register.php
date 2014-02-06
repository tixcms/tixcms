<?php

namespace Users\Forms;

/**
 * Форма регистрации пользователя
 */
class Register extends \App\Form
{
    private $validator;
    
    function init()
    {
        parent::init();
        
        $this->inputs = $this->get_inputs();
        $this->validator = new Validator($this);
        
        $this->actions_view = 'forms/actions/register';
    }

    function get_inputs()
    {
        return array(
            'login'=>array(
                'type'=>'text',
                'label'=>'Логин',
                'placeholder'=>'Логин',
                'rules'=>'trim|required|callback_login_exists',
                'visible'=>$this->settings->users_must_use_login
            ),
            'email'=>array(
                'type'=>'text',
                'label'=>'Email',
                'placeholder'=>'Email',
                'rules'=>'trim|required|valid_email|callback_email_exists'
            ),
            'password'=>array(
                'type'=>'password',
                'label'=>'Пароль',
                'placeholder'=>'Пароль',
                'rules'=>'trim|required',
                'xss'=>FALSE
            ),
        );
    }
    
    /**
     * Проверка почты
     */
    function email_exists($str)
    {
        return $this->validator->email_exists($str);
    }
    
    /**
     * Проверка логина
     */
    function login_exists($str)
    {
        return $this->validator->login_exists($str);
    }
}