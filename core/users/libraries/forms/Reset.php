<?php

namespace Users\Forms;

/**
 * Форма ввода почты для сброса пароля
 */
class Reset extends \App\Form
{
    public $actions_view = 'forms/actions/reset';
    
    public $inputs = array(
        'email'=>array(
            'type'=>'text',
            'label'=>'Email',
            'placeholder'=>'Email',
            'rules'=>'trim|required|valid_email|callback_email_exists'
        )
    );
    
    /**
     * Проверка почты
     */
    function email_exists($str)
    {
        if( $this->auth->email_exists($str) )
		{
			return TRUE;
		}
		else
		{
            $this->form_validation->set_message('email_exists', 'Указанный адрес почты не найден');
			return FALSE;
		}
    }
}