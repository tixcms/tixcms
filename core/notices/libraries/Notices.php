<?php

class Notices
{
    static public $data;
    
    static function get_data()
    {
        if( !self::$data )
        {
            \CI::$APP->load->model('modules/modules_m');
        
            $modules = \CI::$APP->modules_m->by_is_menu(1)->get_all();
            
            $modules_new = array();
            foreach($modules as $module)
            {
                $class = ucfirst($module->url) .'\Notices';
                
                if( class_exists($class) )
                {
                    $inst = \CI::$APP->load->library($class);
                    
                    $modules_new[$module->url] = $inst->count();
                }
            }
            
            self::$data = $modules_new;
        }
    }
    
    /**
     * Есть ли новые уведомления
     */
    static function has($module)
    {
        self::get_data();
        
        return isset(self::$data[$module]) ? self::$data[$module]['count'] > 0 : FALSE;
    }
    
    /**
     * Количество новых уведомлений
     */    
    static function get_count($module)
    {
        self::get_data();
        
        return self::$data[$module]['count'];
    }
    
    /**
     * Текст уведомления
     */
    static function get_label($module)
    {
        self::get_data();
        
        return self::$data[$module]['label'];
    }
    
    /**
     * Возвращает массив всех уведомлений
     */
    static function get_all()
    {
        self::get_data();
        
        return self::$data;
    }
}