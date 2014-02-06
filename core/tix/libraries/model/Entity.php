<?php

namespace Tix\Model;

abstract class Entity 
{
    function __construct($params)
    {        
        $data = $params[0];
        $objects = isset($params[1]) ? $params[1] : array();
        
        if( $data )
        {
            foreach($data as $key => $value)
            {
                $keys = array_keys($objects);
                
                // если есть вложенный объект
                if( count($objects) AND ($relation = $this->prefix_exists($key, $keys)) )
                {
                    $objects_data[$relation][str_replace($relation, '', $key)] = $value;
                }
                else
                {
                    $this->$key = $value;
                }
            }
        }
        
        // создаем вложенный объект
        if( $objects AND isset($objects_data))
        {
            foreach( $objects_data as $relation => $object)
            {
                $entity = $objects[$relation]['entity'];
            
                $this->$objects[$relation]['alias'] = new $entity(array($object));
            }
        }
    }
    
    private function prefix_exists($prefix, $keys = array())
    {
        foreach( $keys as $key )
        {
            if( strstr($prefix, $key) !== FALSE )
            {
                return $key;
            }
        }
        
        return FALSE;
    }
    
    function __call($method, $args)
    {
        if( isset($this->$method) )
        {
            return $this->$method;
        }
        
        if( method_exists($this, $method) )
        {
            return call_user_func_array($method, $args);
        }
        else
        {
            trigger_error('Method '. $method .'() not exists in '. get_class($this));
        }
        
    }

    /**
     * Методы можно вызывать Entity->method
     * Название метода должно быть Class::method()
     */
    function __get($name)
    {
        if( method_exists($this, $name) )
        {
            return $this->$name();
        }
        
        return $this->$name;
    }
}