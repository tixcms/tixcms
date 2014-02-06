<?php

namespace Modules;

use CI;

class Helper 
{
    static $modulesNames = array();
    static $modules;
    static $groups = false;
    static $modules_by_groups;

    function name($url)
    {
        if( empty(self::$modulesNames) )
        {
            $modules = \CI::$APP->modules_m->select('name, url')->get_all();

            foreach($modules as $module)
            {
                self::$modulesNames[$module->url] = $module->name;
            }
            
            self::$modulesNames['categories'] = 'Категории';
        }

        return isset(self::$modulesNames[$url]) ? self::$modulesNames[$url] : $url;
    }
    
    static function active($name)
    {
        
    }
    
    static function get()
    {
        if( empty(self::$modules) )
        {
            \CI::$APP->load->model('modules/modules_m');
            
            $modules = \CI::$APP->modules_m->get_all();
            foreach($modules as $module)
            {
                self::$modules[$module->url] = $module;
            }
        }

        return self::$modules;
    }
    
    static function field($module, $field)
    {
        $modules = self::get();
        
        return isset($modules[$module]) ? $modules[$module]->$field : false;
    }
    
    public static function get_new_items()
    {
        \CI::$APP->load->model('modules/modules_m');
        
        $modules = \CI::$APP->modules_m->by_is_menu(1)->get_all();
        
        $modules_new = array();
        foreach($modules as $module)
        {
            $class = ucfirst($module->url) .'\Notice';
            
            if( class_exists($class) )
            {
                $modules_new[$module->url] = call_user_func(array($class, 'count'));
            }
        }
        
        return $modules_new;
    }
    
    static function get_groups()
    {
        if( !self::$groups )
        {
            \CI::$APP->load->model('modules/modules_groups_m');
            
            $groups = \CI::$APP->modules_groups_m->order_by('position', 'ASC')->get_all();            
            $temp = new \stdClass;
            $temp->id = 0;
            $temp->alias = 'no_group';
            $temp->name = 'Без группы';
            
            if( $groups )
            {
                array_push($groups, $temp);
            }
            else
            {
                $groups[] = $temp;
            }
            
            self::$groups = $groups;
        }
        
        return self::$groups;
    }
    
    static function get_modules_by_groups($by_is_menu = true)
    {
        if( !self::$modules_by_groups )
        {
            \CI::$APP->load->model('modules/modules_m');
            
            // модули в меню
            if( $by_is_menu )
            {
                 \CI::$APP->modules_m->where('is_menu', 1);
            }
            
            $modules = \CI::$APP->modules_m->where('url !=', 'dashboard')->by_is_backend(1)->order_by('position', 'ASC')->get_all();
            
            $modules_by_groups = array();
            if( $modules )
            {
                foreach($modules as $item)
                {
                    if( \CI::$APP->user->module_access($item->url) )
                    {
                        $modules_by_groups[$item->group_alias][] = $item;
                    }
                }
            }
            
            self::$modules_by_groups = $modules_by_groups;
        }
        
        return self::$modules_by_groups;
    }
}