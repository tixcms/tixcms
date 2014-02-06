<?php

namespace Tix;

class Events 
{
    protected $listeners;
    protected $triggered = false;
    
    function register_classes()
    {
        \CI::$APP->load->model('modules/modules_m');
        
        $modules = \CI::$APP->modules_m->get_all();
        
        if( $modules )
        {
            foreach($modules as $module)
            {
                $class = ucfirst($module->url) .'\Events';
                
                if( class_exists($class) )
                {
                    new $class;
                }
            }
        }
    }
    
    function trigger($event, $data = array())
    {
        if( !$this->triggered )
        {
            $this->register_classes();
            
            $this->triggered = TRUE;
        }
        
        if( isset($this->listeners[$event]) )
        {
            foreach($this->listeners[$event] as $listener)
            {
                if( is_string($listener) )
                {
                    call_user_func_array($listener, $data);
                }
                else
                {
                    $listener($data);
                }
            }
        }
    }
    
    function register($event, $callback)
    {
        if( is_array($event) )
        {
            foreach($event as $item=>$item_callback)
            {
                $this->register($item, $item_callback);
            }
        }
        
        $this->listeners[$event][] = $callback;
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}