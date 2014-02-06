<?php

namespace jQuery;

use CI;

class Plugins
{
    private static $instance;
    
    static function init()
    {
        if( !isset(self::$instance) )
        {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
    
    function notify()
    {  
        return array(
            'css'=>array(
                'jquery::pnotify/jquery.pnotify.default.icons.css',
                'jquery::pnotify/jquery.pnotify.default.css'
            ),
            'js'=>array(
                'jquery::plugins/jquery.pnotify.min.js'
            )
        );
    }
    
    function plugin($plugin, $render = FALSE)
    {
        $files = $this->$plugin();
        
        if( $render )
        {
            return $this->render($files);
        }
        else
        {
            $this->add($files);
        }
    }
    
    /**
     * Fancybox
     */
    function fancybox()
    {
        return array(
            'css'=>array(
                'jquery::plugins/fancybox/jquery.fancybox.css'
            ),
            'js'=>array(
                'jquery::plugins/jquery.fancybox.pack.js'
            )
        );
    }
    
    /**
     * Возвращает ассетсы
     */
    function render($files)
    {
        $return = '';
        
        foreach($files as $type=>$type_files)
        {
            if( $type == 'css' )
            {
                foreach($type_files as $file)
                {
                    $return .= \CI::$APP->di->assets->render_css($file);
                }
            }
            else
            {
                foreach($type_files as $file)
                {
                    $return .= \CI::$APP->di->assets->render_js($file);
                }
            }
        }
        
        return $return;
    }
    
    /**
     * Добавляем в массив ассетсов
     */
    function add($files)
    {
        foreach($files as $type=>$type_files)
        {
            if( $type == 'css' )
            {
                foreach($type_files as $file)
                {
                    \CI::$APP->di->assets->css($file);
                }
            }
            else
            {
                foreach($type_files as $file)
                {
                    \CI::$APP->di->assets->js($file);
                }
            }
        }
    }
}