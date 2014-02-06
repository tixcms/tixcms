<?php

namespace Users\App;

class Controller extends \App\Controller
{
    /**
     * Существует ли почта
     */
    function email_exists($str)
    {
        if( $this->auth->check_email($str) )
		{
			return TRUE;
		}
		else
		{
            $this->form_validation->set_message('email_exists', 'Такой адрес почты не найден');
			return FALSE;
		}
    }
}