<?php

namespace Users\Forms;

/**
 * Форма авторизации
 */
class Login extends \App\Form
{
    public $actions_view = 'forms/actions/login';
    
    function init()
    {
        parent::init();
        
        $login_placeholder = 'Почта '. ($this->settings->users_must_use_login ? ' или логин' : '');

        $this->inputs = array(
            'login'=>array(
                'type'=>'text',
                'placeholder'=>$login_placeholder,
                'rules'=>'trim|required',
                'label'=>'E-mail'
            ),
            'password'=>array(
                'type'=>'password',
                'placeholder'=>'Пароль',
                'rules'=>'trim|required|callback_auth_validate',
                'view'=>'users::forms/inputs/login_password',
                'label'=>""
            )
        );
    }
    
    /**
     * Валидация данных
     */
    function auth_validate()
    {
        $login = $this->post('login');
        $password = $this->post('password');
        
        if( !$this->auth->check_user($login, $password) )
        {
            $this->form_validation->set_message('auth_validate', 'Пользователь с указанными данными не найден');
            
            return false;
        }
        
        if( $this->auth->user->is_active == \Users_m::STATUS_NOT_ACTIVATED )
        {
            $message = 'Вы не подтвердили свой email. '. \URL::anchor('users/activation_resend/'. $this->auth->user->id, 'Отправить письмо для подтверждения повторно') .'.';
            
            $this->form_validation->set_message('auth_validate', $message);
            
            return false;
        }
        
        return TRUE;
    }
}