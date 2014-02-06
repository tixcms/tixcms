<?php

namespace Users;

class Helper 
{
    /**
     * Добавление персональных настроек пользователя
     */
    static function fill_user_personal_settings()
    {
        $personal_settings = array();
        foreach(\Modules\Helper::get() as $module)
        {
            if( $settings = \Settings\Helper::get_settings_class($module->url) )
            {
                foreach($settings->inputs as $input)
                {                
                    if( $settings->is_input_personal($input) )
                    {
                        $personal_settings[$input->field] = $input->default;   
                    }
                }
            }
        }
        
        if( $personal_settings )
        {
            if( is_array(\CI::$APP->users_m->settings) )
            {
                $personal_settings = array_merge($personal_settings, \CI::$APP->users_m->settings);
            }
            
            \CI::$APP->user->settings = $personal_settings;
            
            \CI::$APP->users_m->by_id(\CI::$APP->user->id)->set_settings(serialize($personal_settings))->update();
        }
    }
}