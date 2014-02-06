<?php

class Admin_Controller extends Tix\Controllers\Base
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->config('app/app');
        $this->load->config('tix/services/app');
                
        $this->di->set($this->config->item('services'));
    }
    
    /**
     * Страница авторизации в панели управления
     */
    function action_index()
    {
        $this->template->engines = $this->config->item('template_engines');
        
        $this->di->events->trigger('admin.login_page_enter');
        
        // страница для редиректа при успешной авторизации
        $redirect_url = 'admin/dashboard';
        
        // устанавливаем тему
        $this->template->set_theme('admin');
        
        // устанавливаем layout
        $this->template->add_layout('layout');
        
        // если уже авторизован
        if( $this->user->backend_access() AND $this->user->from_backend() )
        {
            $this->redirect($redirect_url);
        }

        $captcha = new Form\Input\Captcha\SimpleCaptcha;

        // обрабатываем форму
        if( $this->input->post('submit') )
        {
            $email = $this->input->post('login');
            $password = $this->input->post('password');

            $need_captcha = $this->settings->security_captcha == 1;
            $captcha_valid = $this->session->userdata($captcha->session_var) == $this->input->post('captcha');

            // авторизация
            if((!$need_captcha OR $captcha_valid) AND $this->auth->check_user($email, $password) )
            {                
                // Авторизуем юзера
                $this->auth->login();

                $this->di->events->trigger('admin.login_success', array(
                    'user_id'=>$this->auth->user->id,
                    'backend'=>TRUE
                ));

                $query = new \URL\Query(array('url'));
                $redirect = $query->get('url');

                $this->session->set_userdata('from_backend', true);

                $this->redirect($redirect ? $redirect : $redirect_url);
            }
            else
            {
                $this->di->events->trigger('admin.login_fail', array(
                    'login'=>$email,
                    'backend'=>true
                ));

                $this->alert(
                    'error',
                    ($need_captcha AND !$captcha_valid)
                        ? 'Неверно введен код'
                        : 'Введены неверные данные'
               );
            }
        }

        $this->render('index', array(
            'captcha'=>$captcha
        ));
    }
    
    /**
     * Разгогинивание юзера
     */
    function action_logout()
    {
        if( $this->user->logged_in )
        {
            $this->di->events->trigger('users.logout', array(
                'user_id'=>$this->auth->user->id,
                'backend'=>TRUE
            ));
            
            $this->auth->logout();
        }

        $this->redirect();
    }
}