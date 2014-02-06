<?php

namespace Admin;

class Help
{
    static function render()
    {
        $ci = \CI::$APP;
        
        // плагины
        $plugins = false;
        
        $pluginClass = '\\'. ucfirst($ci->module) .'\Plugins';
        
        if( class_exists($pluginClass) )
        {
           $reflectedPluginClass = new \ReflectionClass(call_user_func(array($pluginClass, 'init')));
    
            if( $reflectedPluginClass )
            {
                $plugins = $reflectedPluginClass->getMethods();
            
                $i=0;
                foreach($plugins as $plugin)
                {
                    if( $plugin->class != ltrim($pluginClass, '\\') )
                    {
                        unset($plugins[$i]);
                    }
                    $i++;
                }
            } 
        }
                
        list($has_user_help) = \Modules::find('admin/help/user', $ci->module->url, 'views/');
        
        $ci->template->render('admin::help/index', array(
            'plugins'=>$plugins,
            'has_user_help'=>$has_user_help
        ));
    }
}