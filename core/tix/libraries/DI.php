<?php

namespace Tix;

/**
 * Dependency Injection
 */
class DI
{
    /**
     * Services
     */
    protected $_services = array();
    
    /**
     * Services instances
     */
    protected $_instances;
    
    /**
     * Services parameters
     */
    protected $_params;
    
    function __construct($services = array())
    {
        foreach($services as $name=>$definition)
        {
            $this->set($name, $definition);
        }
    }
    
    /**
     * Register a service
     */
    function set($name, $definition = false)
    {
        if( is_array($name) AND !$definition )
        {
            foreach($name as $key=>$value)
            {
                $this->set($key, $value);
            }
            
            return;
        }
        
        if( is_array($definition) )
        {
            $className = $definition['className'];
            $parameters = $definition['parameters'];
            
            $this->_services[$name] = $definition;
            
            $this->_params = $parameters;
        }
        else
        {
            $this->_services[$name] = $definition;
        }
    }
    
    /**
     * Get s service
     */
    function get($name)
    {
        if( !isset($this->_services[$name]) )
        {
            die($name . ' service doesn\'t exist');
        }
        
        // if string
        if( is_string($this->_services[$name]) )
        {
            $params = isset($this->_params[$name]) ? $this->_params[$name] : '';
            
            $this->_instances[$name] = new $this->_services[$name]($params);
        }
        // if closure
        else
        {
            $this->_instances[$name] = $this->_services[$name]();
        }
        
        return $this->_instances[$name];
    }
    
    function has_service($name)
    {        
        return isset($this->_services[$name]);
    }
    
    /**
     * Get a shared service
     */
    function getShared($name)
    {        
        if( !isset($this->_instances[$name]) )
        {            
            return $this->get($name);
        }
        
        return $this->_instances[$name];
    }
    
    /**
     * Set service parametr
     */
    function setParameter($name, int $instance_key, array $params)
    {
        
    }
    
    function __get($name)
    {
        return $this->getShared($name);
    }
}