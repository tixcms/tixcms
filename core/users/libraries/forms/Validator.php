<?php

namespace Users\Forms;

class Validator
{
    public $form;
    
    function __construct($form)
    {
        $this->form = $form;
    }    
    
    /**
     * Проверка логина
     */
    function login_exists($str)
    {
        if( ($this->form->is_update() AND $str == $this->form->entity->login) 
                OR !$this->auth->login_exists($str) )
		{
			return true;
		}
		else
		{
            $this->form->form_validation->set_message('login_exists', 'Такой логин уже используется');
            
			return false;
		}
    }
    
    /**
     * Проверка почты
     */
    function email_exists($str)
    {        
        if( ($this->form->is_update() AND $str == $this->form->entity->email) OR  !$this->auth->email_exists($str) )
		{
			return true;
		}
		else
		{
            $this->form->form_validation->set_message('email_exists', 'Такой адрес почты уже используется');
            
			return false;
		}
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}