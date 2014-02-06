<?php

namespace Tix;

class Plugin
{
    private static $instances = array();
    public $attributes;
    
    protected function __construct(){}
    
    static function init($attributes = array())
    {
        $calledClass = get_called_class();
        
        if( !isset(self::$instances[$calledClass]) )
        {
            self::$instances[$calledClass] = new $calledClass();
        }
        
        self::$instances[$calledClass]->attributes = $attributes;
        
        return self::$instances[$calledClass];
    }
    
    function attribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : FALSE;
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
    
    function __call($method, $params)
    {
        return;
    }
}