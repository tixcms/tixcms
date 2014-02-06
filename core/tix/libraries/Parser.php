<?php

namespace Tix;

class Parser
{
    private $left_delimiter = '{{';
    private $right_delimiter = '}}';
    private static $instance = null;
    private $data;
    
    static function instance()
    {
        if( !self::$instance )
        {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
    
    function parse($str, $data = array())
    {
        $this->data = $data;
        
        $replaces = array();
        
        $pattern = '/'. $this->left_delimiter 
                                .'[^'. $this->left_delimiter . $this->right_delimiter .']+.'
                      . $this->right_delimiter 
                    .'/i';
        preg_match_all($pattern, $str, $matches);
        
        $matches = array_values(array_unique($matches[0]));
        
        if( empty($matches) )
        {
            return $str;
        }
        
        $i=0;
        foreach($matches as $match)
        {
            $pos = strpos($match, ' ');
            $plugins[$i] = substr($match, strlen($this->left_delimiter), $pos - strlen($this->left_delimiter));

            parse_str(
                    str_replace(
                        array('"', ' '), 
                        array('', '+'),
                        str_replace('" ', '"&', trim(substr($match, $pos + 1), '}'))
                    ),
                $params[$i]
            );
            $i++;
        }
        
        $i=0;
        foreach($plugins as $plugin)
        {
            if( strpos($plugin, ':') === FALSE )
            {
                $replaces[$i] = isset($this->data[$plugin]) ? $this->data[$plugin] : $matches[$i];
            }
            else
            {
                list($class, $method) = explode(':', ucfirst($plugin));
                $class = $class .'\Plugins';
                if( class_exists($class) )
                {
                    $plugin_object = call_user_func(array($class, 'init'), $params[$i]);
                    
                    $callable = array($plugin, $method);
                    
                    if( is_callable($callable, true) )
                    {
                        $replaces[$i] = $plugin_object->$method();
                    }
                    else
                    {
                        $replaces[$i] = $matches[$i];
                    }
                }
                else
                {
                    $replaces[$i] = $matches[$i];
                }
            }
            
            $i++;
        }
        
        $str = str_replace($matches, $replaces, $str);
        
        return $str;
    }
}