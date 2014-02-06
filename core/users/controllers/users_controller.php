<?php

use Users\Config;

class Users_Controller extends App\Controller
{
    /**
     * Просмотр профиля пользователя
     * 
     * @param mixed Логин или ID пользователя
     */
    function action_view($identity = FALSE)
    {
        if( !$identity )
        {
            show_404();
        }
        
        if( !$user = $this->identify_user($identity) )
        {
            show_404();
        }
        
        // устанавливаем вложенный layout
        $this->template->add_layout('profile/layout');
        
        // стили
        $this->di->assets->css('profile.css');
        
        $this->render('profile/index', array(
            'user'=>$user
        ));
    }
    
    /**
     * Регистрация пользователя
     */
    function action_register()
    {
        // если юзер уже авторизован
        if( $this->user->logged_in )
        {
            $this->redirect();
        }
        
        // если отключена регистрация
        if( !$this->settings->users_registration )
        {
            $this->alert('attention', 'Регистрация закрыта');

            $this->forward('pages/errors/general', array(
                'message'=>'Регистрация закрыта',
                'header'=>'Внимание',
                'type'=>'attention'
            ));
            
            return;
        }
        
        $form = $this->load->library('Users\Forms\Register');
        
        // обрабатываем форму
        if( $form->submitted() )
        {            
            // валидация формы
            if( $form->validate() )
            {
                // данные
                $data = array(
                    'login'=>$form->get('login'),
                    'email'=>$form->get('email'),
                    'password'=>$form->get('password'),
                );
                
                // регистрируем юзера
                $this->auth->register($data);
                
                $event_data = array_merge(
                    array(
                        'activation'=>URL::site_url(
                            'users/activate/'
                            . $this->auth->data['id'] .'/' .$this->auth->data['activation_code']
                        ),
                        'user_id'=>$this->auth->data['id']
                    ),
                    $data
                );
                
                $this->di->events->trigger('users.register', $event_data);
                
                Email\Templates::send('users', 'users-register', $event_data);
                
                // редиректим
                $this->redirect('users/register_success');
            }
            else
            {
                $this->alert('error', $form->get_errors());
            }
        }
        
        $this->di->seo->add_title('Регистрация на сайте');

        $this->render('register', array(
            'form'=>$form
        ));
    }
    
    /**
     * Resend activation email
     */
    function action_activation_resend($user_id = false)
    {
        if( !$user_id OR !is_numeric($user_id) )
        {
            show_404();
        }
        
        if( !$user = $this->users_m->by_id($user_id)->get_one() )
        {
            show_404();
        }
        
        if( $user->is_active == \Users_m::STATUS_ACTIVATED )
        {
            show_404();
        }
        
        $activation_code = $this->auth->generate_activation_code();
        
        $this->users_m->by_id($user_id)->set_activation_code($activation_code)->update();
        
        Email\Templates::send('users', 'activation-resend', array(
            'login'=>$user->login,
            'activation'=>URL::site_url('users/activate/'. $user->id .'/'. $activation_code),
            'email'=>$user->email
        ));
        
        $this->alert_flash('success', 'Повторное письмо для активации аккаунта отправлено');
        
        $this->referer();
    }
    
    /**
     * Страница ввода почты для восстановления пароля
     */
    function action_reset()
    {
        if( $this->user->logged_in )
        {
            $this->redirect();
        }
        
        $form = $this->load->library('Users\Forms\Reset');
        
        $this->alert->set('attention', 'Для восстановления пароля введите свой регистрационный e-mail. На него придет письмо с дальнейшими действиями.', '', false);

        // обрабатываем форму
        if( $form->submitted() )
        {
            // валидация формы
            if( $form->validate() )
            {
                // начинаем процесс смены пароля
                $code = $this->auth->start_reset($form->get('email'));
                
                $event_data = array(
                    'reset_link'=>$this->url->site_url(
                        'users/reset_password/'. $this->auth->data['user_id'] .'/'. $this->auth->data['token']
                    ),
                    'login'=>$this->auth->data['login'],
                    'email'=>$this->auth->data['email']
                );
                
                $this->di->events->trigger('reset_password', $event_data);
                
                Email\Templates::send('users', 'users-reset-password', $event_data);
                    
                // обновляем страницу
                $this->redirect('users/reset_send');
            }
            else
            {
                $this->alert('error', $form->get_errors());
            }
        }
        
        $this->di->seo->add_title('Восстановление пароля');
        
        $this->render('reset', array(
            'form'=>$form
        ));
    }
    
    /**
     * Страница, оповещающая, что отправлено письмо для восстановления пароля
     */
    function action_reset_send()
    {
        $this->render('reset_send');
    }
    
    /**
     * Форма смены пароля
     */
    function action_reset_password($user_id = '', $token = '')
    {
        // если юзер авторизован
        if( $this->user->logged_in )
        {
            show_404();
        }
        
        // если нет такого пользователя
        if( !$user = $this->users_m->by_id($user_id)->get_one() )
        {
            show_404();
        }
        
        // если не совпадает токен
        if( !$this->auth->check_reset_token($user, $token)  )
        {
            show_404();
        }
        
        $this->alert('info', 'Введите новый пароль');
        
        $form = $this->load->library('Users\Forms\Password\Reset', array(
            'entity'=>$user, 
            'model'=>$this->users_m
        ));
    
        // обрабатываем форму
        if( $form->submitted() )
        {
            // валидация формы
            if( $form->save() )
            {
                $this->alert_flash('success', 'Пароль успешно изменен');
                
                // редирект
                $this->redirect('users/login');
            }
            else
            {
                $this->alert('error', $form->get_errors());
            }
        }
        
        $this->render('reset_password', array(
            'form'=>$form
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
                'user_id'=>$this->user->id
            ));

            $this->auth->logout();
        }

        $this->redirect();
    }
    
    /**
     * Активация аккаунта
     */
    function action_activate($user_id, $code)
    {
        if( !$this->auth->activate($user_id, $code) )
        {
            show_404();
        }

        $this->alert_flash('success', 'Ваш аккаунт успешно активирован. Теперь вы можете войти.');

        $this->title('Активация аккаунта');

        $this->redirect('users/login');
    }
    
    /**
     * Страница об успешной регистрации
     */
    function action_register_success()
    {
        $this->render('register_success');
    }
    
    /**
     * Ищем пользователя по данным
     */
    function identify_user($identity)
    {
        // ищем пользователя по id или логину
        is_numeric($identity) ? $this->users_m->by_id($identity) : $this->users_m->by_login($identity);
        
        return $this->users_m->get_one();
    }
}