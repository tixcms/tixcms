<?php

namespace Users;

use CI;

class Permissions 
{
    private $CI;
    private $permissions = array();
    private $group_alias;
    
    function init()
    {
        CI::$APP->load->model('users/permissions_m');
        
        if( CI::$APP->user->logged_in )
        {
            $row = CI::$APP->permissions_m->where('group_alias', CI::$APP->user->group_alias)->get_one('permissions');

            $this->permissions = isset($row->permissions) ? unserialize($row->permissions) : array();
        }
    }
    
    function get_permissions()
    {
        return $this->permissions;
    }

    function get()
    {
        return array(
            'add_user'=>'Добавить пользователя',
            'edit_user'=>'Редактировать пользователя',
            'delete_user'=>'Удалить пользователя'
        );
    }

    /**
     * Проверяет права на доступ к модулю для административных нужд
     * 
     */
    function has_module_access( $module = false )
    {
        // если админ, то можно все
        if( CI::$APP->user->group_alias == 'admins' )
        {
            return true;
        }
        
        // если не указан модуль, то проверяем текущий
        if( !$module )
        {
            $module = CI::$APP->module;
        }

        // проверяем доступ к модулю
        if( !isset($this->permissions[$module]) OR $this->permissions[$module] != 1 )
        {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Проверяет права на доступ в админку
     * 
     */
    function has_backend_access()
    {
        return $this->has_module_access('dashboard');
    }
}