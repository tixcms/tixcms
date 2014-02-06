<?php

namespace Users;

use CI;

class Entity extends \Tix\Model\Entity 
{
    static $guest_name = 'Гость';
    
    function from_backend()
    {
        return CI::$APP->session->userdata('from_backend') == TRUE;
    }

    function settings($key)
    {
        if( !isset($this->settings[$key]) )
        {
            \Users\Helper::fill_user_personal_settings();
        }
        
        return $this->settings[$key];
    }

    /**
     * Авторизован ли юзер
     */
    function logged_in()
    {
        return $this->id > 0;
    }
    
    /**
     * Гость ли юзер
     */
    function is_guest()
    {
        return $this->id == 0;
    }
    
    function can_access($access)
    {        
        return $this->module_access($access);
    }
    
    /**
     * Проверяет есть ли доступ к модулю
     */
    function module_access($module = false)
    {
        return CI::$APP->permissions->has_module_access($module);
    }
    
    /**
     * Проверяет есть ли доступ к админке
     */
    function backend_access()
    {
        return CI::$APP->permissions->has_backend_access();
    }
    
    function avatar_url()
    {
        // если загружен аватар
        if( $this->avatar )
        {
            $avatar = Config::AVATAR_PATH . $this->avatar;
        }
        // пробуем использовать граватар или изображение по умолчанию
        else
        {
            $avatar = \Users\Gravatar::get($this->email, '', 'identicon');
        }
        
        return \URL::site_url($avatar);
    }
    
    function profile_url()
    {
        return 'users/view/'. $this->id;
    }
    
    function is_current()
    {
        return $this->id == CI::$APP->user->id;
    }
    
    function group_label()
    {
        return Users_Groups::label($this->group_alias);
    }
    
    function is_in_group($groups)
    {
        if( is_array($groups) )
        {
            return in_array($this->group_alias, $groups);
        }
        else
        {
            return $this->group_alias == $groups;
        }
    }
}