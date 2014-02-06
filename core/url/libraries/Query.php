<?php

namespace URL;

class Query 
{
    private $params = array();
    private $query;
    
    /**
     * mixed params массив или строка
     */
    function __construct($params = false)
    {
        if( $params )
        {
            $this->valid_params($params);
        }
    }
    
    function valid_params($params)
    {
        if( is_array($params) )
        {
            $this->params = array_merge($this->params, array_fill_keys($params, ''));
        }
        else
        {
            $this->params[$params] = '';
        }
        
        $this->_parseUrlQuery();
        
        return $this;
    }
    
    function _parseUrlQuery()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        // получаем параметры из ?p=1&q=2
        $this->query = parse_url($uri, PHP_URL_QUERY);

        // params =  array('p' => 1, 'q' => 2)
        parse_str($this->query, $params);

        // соединяем заданные параметры и полученные через строку
        $this->params = array_merge($this->params, $params);
    }
    
    function set($key, $value)
    {
        $this->params[$key] = $value;   
    }
    
    function get($key)
    {
        return isset($this->params[$key]) ? $this->params[$key] : false;
    }
    
    /**
     * DEPRECATED
     */
    function generateUriQuery($paramKey = '', $paramValue = '', $skip_params = array())
    {
        return $this->generate($paramKey, $paramValue, $skip_params);
    }
    
    /**
     * Генерит строку параметров
     * 
     * @param string имя параметра
     * @param string текущее значение
     * @param array параметры, которые будут пропущены при выводе
     */
    function generate($paramKey = '', $paramValue = '', $skip_params = array())
    {        
        $query = array();
        foreach($this->params as $key=>$value)
        {
            if( in_array($key, $skip_params) )
            {
                continue;
            }
            
            if( $value OR $key == $paramKey )
            {
                if( $paramKey != $key OR ($paramKey == $key AND $paramValue) )
                {
                    if( is_array($value) )
                    {
                        foreach($value as $key2=>$value2)
                        {
                            $query[] = $key == $paramKey 
                                ? $key.'['. $key2 .']'.'='.$value2 
                                : $key.'['. $key2 .']'.'='.$value2;
                        }
                    }
                    else
                    {
                        $query[] = $key == $paramKey ? $paramKey.'='.$paramValue : $key.'='.$value;
                    }
                }
            }
        }
        
        return count($query) ? implode('&', array_filter($query)) : '';
    }
}