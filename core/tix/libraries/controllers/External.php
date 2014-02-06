<?php

namespace Tix\Controllers;

class External
{
    public $params_offset = 4;
    
    function __construct($params = array())
    {
        foreach($params as $key=>$value)
        {
            $this->$key = $value;
        }
    }
    
    function run()
    {
        $action = 'action_'. $this->action;
        $params = array_slice($this->uri->segment_array(), $this->params_offset);
        
        if( method_exists($this, 'init') )
        {
            $this->init();
        }
        
        call_user_func_array(array($this, 'action_'.$this->action), $params);
        
        exit;
    }
    
    
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
    
    function __call($method, $args)
    {
        show_404();
    }
}