<?php

/**
 * Профиль пользователя
 */
class Edit_Controller extends Users\App\Controller\Profile
{
    function __construct()
    {
        parent::__construct();
        
        $this->template->add_layout('profile/edit/layout');
    }
    
    /**
     * Первая страница профиля пользователя
     */
    function action_index($data = false)
    {
        // если гость редиректим на авторизацию
        if( $this->user->is_guest )
        {
            $this->redirect('users/login?redirect='. $this->uri->uri_string());
        }
        
        $form = $this->load->library('Users\Forms\Profile', array(
            'entity'=>$this->user, 
            'model'=>$this->users_m
        ));
        
        if( $form->submitted() )
        {
            if( $form->save() )
            {
                // сбрасываем сессию
                $this->session->unset_userdata('user');

                // информационные сообщение
                $this->alert_flash('success', 'Изменения сохранены');

                // редиректим
                $this->referer();
            }
            else
            {
                $this->alert('error', $form->get_errors());
            }
        }

        // хлебные крошки
        $this->crumb('Редактирование', 'users/profile/edit');

        $this->render('profile/edit/index', array(
            'form'=>$form
        ));
    }
    
    /**
     * Редактирование пароля
     */
    function action_password()
    {
        $form = new Users\Forms\Password\Change($this->user, $this->users_m);
        
        if( $form->submitted() )
        {
            if( $form->save() )
            {
                $this->alert_flash('success', 'Пароль был успешно изменен');
                
                $this->referer();
            }
            else
            {
                $this->alert('error', $form->get_errors());
            }
        }
        
        $this->render('profile/edit/password', array(
            'form'=>$form
        ));
    }
}