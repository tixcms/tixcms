<?php

namespace Modules\Blocks;

class Nav extends \Block
{
    protected $options = array(
        'limit'=>4
    );
    
    function data()
    {
        $this->load->model('modules/modules_m');
        
        // модули доступные пользователю
        $user_modules = false;
        if( !$this->user->is_in_group('admins') )
        {
            $user_modules = array_keys($this->permissions->get_permissions());
        }
        
        // основные пункты меню
        $user_modules ? $this->modules_m->where_in('url', $user_modules) : '';
        $nav = $this->modules_m
                                    ->select('name, url')
                                    ->by_is_menu(1)
                                    ->order_by('ord', 'ASC')
                                    ->limit($this->options['limit'])
                                    ->get_all();
        
        // дополнительные пункты меню
        $user_modules ? $this->modules_m->where_in('url', $user_modules) : '';
        $more_nav = $this->modules_m
                                    ->select('name, url')
                                    ->by_is_menu(1)
                                    ->order_by('ord', 'ASC')
                                    ->limit(100)
                                    ->offset($this->options['limit'])
                                    ->get_all();
                                        
        $modules_new = \Modules\Helper::get_new_items();
        
        return array(
            'nav'=>$nav,
            'more_nav'=>$more_nav,
            'modules_new'=>$modules_new
        );
    }
}