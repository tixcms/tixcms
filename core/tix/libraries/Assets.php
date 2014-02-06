<?php

namespace Tix;

use HTML\Tag;

class Assets 
{
    private $folders = array(
        'img'=>'img/',
        'css'=>'css/',
        'js'=>'js/'
    );
    
    private $files = array(
        'js'=>array(),
        'css'=>array()
    );
    
    function __construct($config = array())
    {
        if( isset($config['folders']) )
        {
            $this->folders = $config['folders'];
        }
    }
    
    /**
     * Добавляет css в массив
     */
    function css($href, $attrs = array(), $version = '')
    {
        $this->add('css', $href, $attrs, $version);
    }
    
    /**
     * Добавляет js в массив
     */
    function js($href, $attrs = array(), $version = '')
    {        
        $this->add('js', $href, $attrs, $version);
    }
    
    /**
     * Вовзращает тег js
     */
    function render_js($href, $attrs = array(), $version = '')
    {
        return $this->get('js', $href, $attrs, $version);
    }
    
    /**
     * Возвращает тег css
     */
    function render_css($href, $attrs = array(), $version = '')
    {
        return $this->get('css', $href, $attrs, $version);
    }
    
    function set_param($key, $value)
    {
        $this->$key = $value;
    }
    
    private function add($type, $src, $attrs = array(), $version = '')
    {
        if( !array_key_exists($src, $this->files[$type]) )
        {
            $this->files[$type][$src] = $this->get($type, $src, $attrs, $version);
        }
    }
    
    private function get($type, $src, $attrs = array(), $version = '')
    {
        if( strstr($src, '//') === FALSE )
        {
            $path = $this->get_path($type, $src, $version);
            
            //$path = ENVIRONMENT == 'development' ? '../../' . $path : $path;            
            $path = \CI::$APP->di->url->site_url($path, false) . ($version ? '?'. $version : '');
        }
        else
        {            
            $path = $src;
        }
        
        return Tag::$type($path, $attrs);
    }

    function get_path($type, $src, $version = '')
    {
        if( strpos($src, '::') === FALSE OR strpos($src, '::') !== 0 )
        {
            // если текущий модуль
            if( strpos($src, '::') === FALSE )
            {
                $module = $this->module->url;
                $version = $version ? $version : $this->module->version;
            }
            else
            {
                $parts = explode('::', $src);
                $module = $parts[0];
                $src = $parts[1];
            }

            $module = $module .'/';

            $modules_locations = array_keys(\Modules::$locations);

            $path = $modules_locations[0] . $this->template->theme .'/assets/'.  $module . $this->folders[$type] . $src;

            if( !file_exists($path) )
            {
                $path = $modules_locations[1] . $module . $this->folders[$type] . $src;

                if( !file_exists($path) )
                {
                    $path = $modules_locations[2] . $module . $this->folders[$type] . $src;
                }
            }
        }
        else
        {
            $src = substr($src, 2, strlen($src));

            $path = $this->config->item('theme_location')
                . $this->template->theme .'/'
                . $this->folders[$type]
                . $src;
        }

        return $path;
    }
    
    /**
     * Все assets
     * 
     * @type mixed false|css|js
     */
    function all($type = false)
    {
        if( $type )
        {
            return implode("\n", $this->files[$type]);
        }
        else
        {
            return implode("\n", $this->files['css']) ."\n". implode("\n", $this->files['js']);
        }
    }
        
    function img($src, $attrs = array())
    {        
        return $this->get('img', $src, $attrs);
    }

    function img_path($src)
    {
        return $this->get_path('img', $src);
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}