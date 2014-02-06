<?php

class Settings_Controller extends Admin\Controller 
{    
    public $has_help = TRUE;
    public $has_settings = TRUE;
    
    function action_index()
    {
        $this->action_settings();
    }
    
    function action_lang($lang = false)
    {
        if( $lang AND in_array($lang, array('ru', 'en')) )
        {            
            $settings = $this->user->settings;
            $settings['admin_language'] = $lang;
            
            $this->users_m->by_id($this->user->id)->set_settings(serialize($settings))->update();
        }
        
        $this->referer();
    }
}