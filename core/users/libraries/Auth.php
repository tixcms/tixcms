<?php

namespace Users;

use CI;

class Auth
{    
    function init()
    {
        $this->load->model('users/users_m');
        
        CI::$APP->user = $this->users_m->by_id($this->session->userdata('user_id'))->get_one();

        // отмечаем последний визит пользователя
        if( $this->user->logged_in )
        {
            $this->users_m->last_visit_time_update();
        }
    }
        
    /**
     * Разавторизация
     */
    function logout()
    {
        $this->session->sess_destroy();
    }
    
    /**
     * Проверяет существует ли юзер
     */
    function check_user($credentials, $password)
    {
        // берем из базы юзера
        $user = $this->users_m->by_email($credentials)->or_where('login', $credentials)->get_one();

        // проверяем есть ли юзер и совпадает ли пароль
        if( !$user OR $user->password != $this->dohash($password) )
        {
            return FALSE;
        }
        
        $this->user = $user;

        return TRUE;
    }
    
    /**
     * Авторизация
     */
    function login($id = false)
    {
        $id = $id ? $id : $this->user->id;
        
        // обновляем данные
        $this->users_m->by_id($id)->update(array(
            'last_ip'=>$this->session->userdata('ip_address'),
            'user_agent'=>$this->session->userdata('user_agent')
        ));
        
        $this->session->set_userdata('user_id', $id);
    }
    
    /**
     * Регистрация пользователя
     */
    function register($data = array())
    {        
        $this->data = array_merge(
            $data,
            array(
                'activation_code'=>$this->generate_activation_code(),
                'password'=>$this->dohash($data['password']),
                'user_agent'=>$this->session->userdata('user_agent'),
                'group_alias'=>'users',
                'register_date'=>time(),
                'is_active'=>\Users_m::STATUS_NOT_ACTIVATED
            )
        );
        
        // создаем пользователя
        $user_id = $this->users_m->insert($this->data);
        
        $this->data['id'] = $user_id;
    }
    
    public function generate_activation_code()
    {
        return \Helpers\String::random('unique');
    }
    
    /**
     * Активация аккаунта юзера
     */
    function activate($user_id, $code)
    {
        if( $this->users_m->by_id($user_id)->by_activation_code($code)->count() == 1 )
        {
            $this->users_m
                        ->by_id($user_id)
                        ->set('activation_code', '')
                        ->set('is_active', \Users_m::STATUS_ACTIVATED)
                        ->update();
                        
            $this->user->id = $user_id;
            
            $this->login();
            
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Начало процесса сброса пароля
     */
    function start_reset($email)
    {
        // юзер
        $user = $this->users_m->by_email($email)->get_one();
        
        // генерируем токен
        $token = $this->_generate_token();
        
        // обновляем данные пользователя
        $this->users_m
                    ->by_id($user->id)
                    ->set('reset_token', $token)
                    ->update();
        
        // данные
        $this->data = array(
            'token'=>$token,
            'email'=>$email,
            'login'=>$user->login,
            'user_id'=>$user->id
        );  
    }
    
    /**
     * Генерирует рендомный токен для смены пароля
     */
    private function _generate_token()
    {
        return sha1(\Helpers\String::random('unique'));
    }
    
    /**
     * Шифрование пароля
     */
    function dohash($string)
    {
        return sha1(($string));
    }
    
    /**
     * Проверяем, что почта существует
     */
    function email_exists($email)
    {
        return $this->users_m->by_email($email)->count() > 0;
    }
    
    /**
     * Проверяем, что логин существует
     */
    function login_exists($login)
    {
        return $this->users_m->by_login($login)->count() > 0;
    }
    
    /**
     * Проверяет, что пользователь может изменить свой пароль
     */
    function check_reset_token($user, $token)
    {
        return $this->users_m->by_id($user->id)->where('reset_token', $token)->count() == 1;
    }
    
    function __get($key)
    {
        return CI::$APP->$key;
    }
}