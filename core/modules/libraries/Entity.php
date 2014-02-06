<?php

namespace Modules;

use CI;

class Entity extends \Tix\Model\Entity 
{
    function name()
    {
        if( is_array($this->name) )
        {
            $lang = \CI::$APP->config->item('language');
            
            return $this->name[$lang];
        }
        elseif( strpos($this->name, '{') === 0 )
        {
            $names = json_decode($this->name);
            $lang = \CI::$APP->config->item('language');
            
            return $names->$lang;
        }
        else
        {
            return $this->name;
        }
    }
    
    function desc()
    {
        if( is_array($this->desc) )
        {
            $lang = \CI::$APP->config->item('language');
            
            return $this->desc[$lang];
        }
        elseif( strpos($this->desc, '{') === 0 )
        {
            $names = json_decode($this->desc);
            $lang = \CI::$APP->config->item('language');
            
            return $names->$lang;
        }
        else
        {
            return $this->desc;
        }
    }
    
    function description()
    {
        if( is_array($this->description) )
        {
            $lang = \CI::$APP->config->item('language');
            
            return $this->description[$lang];
        }
        elseif( strpos($this->description, '{') === 0 )
        {
            $names = json_decode($this->description);
            $lang = \CI::$APP->config->item('language');
            
            return $names->$lang;
        }
        else
        {
            return $this->description;
        }
    }
    
    function full_url()
    {
        return 'admin/'. $this->url;
    }
    
    function is_current()
    {
        return CI::$APP->uri->segment(2) == $this->url;
    }
    
    function __toString()
    {
        return (string)$this->url;
    }
}