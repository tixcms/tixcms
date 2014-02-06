<?php

namespace Tix;

use CI;

class Template
{
    public $theme = '';
    
    public $layout = array();

    private $data = array();
    
    private $meta = array();
    
    public $parse = true;
    
    public $engines = array();
    

    function __construct()
    {
        $this->engines = CI::$APP->config->item('template_engines');
        
        $this->set('DI', CI::$APP->di);
    }
    
    function parse($str, $data = array())
    {
        $parser = \Tix\Parser::instance();
        return $parser->parse($str, $data);
    }
    
    /**
     * Присвоение значений переменной для использования в шаблоне
     */
    function set($name, $data)
    {
        $this->data[$name] = $data;
        return $this;
    }
    
    function view($view, $data = array())
    {        
        if( is_array($data) )
        {
            $data = array_merge($this->data, $data);
        }
        
        // если рендер из модуля, то грузим из соответсвующего модуля
        if( strstr($view, '::') === false )
        {
            $view = CI::$APP->module .'::'. $view;
        }
                
        // расположение модулей
        $modules_locations = array_keys(\Modules::$locations);
        
        $admin_folder = CI::$APP->is_backend ? 'admin/' : '';

        list($module, $view) = explode('::', $view);
        $module = $module ? $module .'/' : '';
        
        if( CI::$APP->is_backend )
        {
            $templates_dirs[] = 'themes/' . $module .'views/';
        }
        
        $templates_dirs[] = 'themes/' . $this->theme .'/views/'. $module;
        $templates_dirs[] = 'addons/' . $module .'views/' . $admin_folder;
        $templates_dirs[] = 'core/' . $module .'views/' . $admin_folder;   

        foreach($templates_dirs as $key=>$dir)
        {
            if( !file_exists($dir) )
            {
                unset($templates_dirs[$key]);
            }
        }        

        $ext = pathinfo($view, PATHINFO_EXTENSION);
        
        if( !array_key_exists($ext, $this->engines) )
        {
            $ext = 'default';
        }
        
        $engine = is_callable($this->engines[$ext]) ? $this->engines[$ext]() : $this->engines[$ext];
        
        return $engine->view($view, $data, $templates_dirs);
    }
    
    function call_view()
    {
        $class = ucfirst($this->theme);
        
        if( class_exists($class) )
        {
            $view = new $class;
            
            if( method_exists($view, 'init') )
            {
                $view->init();
            }
        }
        
        if( !isset(\CI::$APP->module->url) )
        {
            return;
        }
        
        $r = array_merge(\CI::$APP->uri->rsegments, array(\CI::$APP->module->url));        
        $middle = array_diff(\CI::$APP->uri->segments, $r);
        
        $class = ucfirst($this->theme) 
                . '\Views\\'. ucfirst(\CI::$APP->module->url);
                /*
                . ( $middle ? '\\'. ucfirst(implode('', $middle)) : '' )
                . ((CI::$APP->module != CI::$APP->controller OR $middle) ? '\\'. ucfirst(CI::$APP->controller): '');
                */
            
        $method = 'action_' . CI::$APP->action;
        
        if( class_exists($class) )
        {
            $view = new $class;
            
            if( method_exists($view, $method) )
            {
                $view->$method($this->data);
            }
        }
    }
    
    /**
     * Вывод шаблона
     */
    function render($view = false, $data = array())
    {
        // если view не указано, то береться название экшена
        if( !$view OR is_array($view) )
        {
            $this->view = CI::$APP->module == CI::$APP->controller 
                            ? CI::$APP->action 
                            : CI::$APP->controller .'/'. CI::$APP->action;
            $data = is_array($view) ? $view : array();
        }
        else
        {
            $this->view = $view;
        }
        
        // данные
        $this->data = array_merge($this->data, $data);
        
        $this->call_view();
        
        // загружаем контент
        $this->data['content'] = $this->view($this->view, $this->data);
        
        // загружаем весь шаблон
        $i=1;
        foreach($this->layout as $layout)
        {
            if( $i == count($this->layout) )
            {
                if( $this->parse )
                {
                    echo $this->parse($this->view($layout, $this->data));   
                }
                else
                {
                    echo $this->view($layout, $this->data);   
                }       
            }
            else
            {
                if( $this->parse )
                {
                    $this->data['content'] = $this->parse($this->view($layout, $this->data));
                }
                else
                {
                    $this->data['content'] = $this->view($layout, $this->data);
                }
            }
            
            $i++;
        }
    }

    /**
     * Добавление шаблона в очередь
     */
    function add_layout($layout)
    {
        if( is_array($layout) )
        {
            foreach($layout as $item)
            {
                $this->add_layout($item);
            }
            
            return;
        }
        
        array_unshift($this->layout, $layout);
        return $this;
    }
    
    /**
     * Убирает последний шаблон из очереди
     */
    function remove_layout()
    {
        array_shift($this->layout);
        return $this;
    }

    /**
     * Установка темы
     */
    function set_theme($theme)
    {
        $this->theme = $theme;
        return $this;
    }
}