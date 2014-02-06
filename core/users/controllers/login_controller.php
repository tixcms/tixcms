<?php

class Login_Controller extends App\Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->seo->add_title('Вход на сайт');
    }
    
    /**
     * Страница авторизации пользователя
     */
    function action_index()
    {
        // редиректим авторизованного пользователя
        if( $this->user->logged_in )
        {
            $this->redirect();
        }
             
        $form = $this->load->library('Users\Forms\Login');

        // обрабатываем форму
        if( $form->submitted() )
        {
            // валидация
            if( $form->validate() )
            {
                // Авторизуем юзера
                $this->auth->login();

                $this->events->trigger('users.login_success', array(
                    'user_id'=>$this->auth->user->id
                ));
                
                // редиректим обратно на страницу
                $query = $this->load->library('URL\Query', array('redirect'));
                
                $redirect = $query->get('redirect');
                
                $this->redirect($redirect ? $redirect : '');
            }
            else
            {
                $this->events->trigger('users.login_fail', array(
                    'login'=>$form->post('login')
                ));

                $this->alert('error', $form->get_errors());
            }
        }
        
        $this->render(array(
            'form'=>$form
        ));
    }
}