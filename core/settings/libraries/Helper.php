<?php

namespace Settings;

class Helper 
{
    /**
     * Возвращает объект класса настроек или 
     */
    static function get_settings_class($module, $params = array())
    {
        if( !$module )
        {
            return;
        }
        
        $class_name = $module == 'settings' ? 'Settings' : ucfirst($module) . '\Settings';

        if( !class_exists($class_name) )
        {
            return false;    
        }

        return \CI::$APP->load->library($class_name, $params);
    }
}