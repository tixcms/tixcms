<?php

namespace Pages;

class Entity extends \Categories\Entity
{
    /**
     * Check if current user has access to page
     */
    function has_access()
    {
        if( !isset($this->access) )
        {
            return true;
        }
        
        return $this->access == 'all' 
            OR $this->access == '' 
            OR strstr($this->access, '{'. \CI::$APP->user->group_alias() .'}') !== false;
    }
    
    public function is_current($module = false)
    {
        return strpos(\CI::$APP->uri->uri_string(), $this->url($module)) === 0;
    }
    
    function full_url()
    {
        if( $this->is_main )
        {
            return '';
        }
        else
        {
            return ($this->pre_url ? $this->pre_url . '/' : '') . $this->url;
        }
    }
    
    function url($module = false)
    {
        if( $module )
        {
            if( isset(\CI::$APP->router->all_pages[$module]) )
            {
                return \CI::$APP->router->all_pages[$module]->url 
                    ? \CI::$APP->router->all_pages[$module]->url
                    : \CI::$APP->router->all_pages[$module]->module;
            } 
            else
            {
                return $module;
            }
        }
        else
        {
            if( $this->module )
            {
                return $this->url;
            }
            else
            {
                return ($this->pre_url ? $this->pre_url . '/' : '') . $this->url;
            }
        }        
    }
}