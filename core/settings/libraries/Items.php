<?php

namespace Settings;

class Items
{
    function __construct()
    {
        \CI::$APP->load->model('settings/settings_m');
        
        $settings = \CI::$APP->settings_m->get_all();
        
        if( $settings )
        {
            foreach($settings as $setting)
            {
                $this->{$setting->id} = $setting->value;
            }
        }
    }
}