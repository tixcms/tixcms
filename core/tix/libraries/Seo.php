<?php

namespace Tix;

class Seo {
    
    private $title = array();
    private $delimiter = ' » ';
    private $add_method;
    private $description;
    private $keywords;
    private $default;
    private $metadata = array();
    
    function __construct($config = array())
    {
        $this->add_method = 'append';
        
        $this->_set_config($config);
        
        if( $this->default )
        {
            $this->set_title($this->default['title']);
            $this->set_description($this->default['description']);
            $this->set_keywords($this->default['keywords']);
        }
    }
    
    private function _set_config($config)
    {
        foreach($config as $key => $value)
        {
            $this->$key = $value;
        }
    }
    
    function set_description($description)
    {
        $this->description = $description;
    }
    
    function set_keywords($keywords)
    {
        $this->keywords = $keywords;
    }
    
    function description()
    {
        return $this->description ? \HTML\Tag::description($this->description) : '';
    }
    
    function keywords()
    {
        return $this->keywords ? \HTML\Tag::keywords($this->keywords) : '';
    }
    
    /**
     * Вывод метаданных
     */
    function metadata()
    {
        return $this->description() 
            . $this->keywords() 
            . implode("\n", $this->metadata);
    }
    
    function add_metadata($metadata)
    {
        $this->metadata[] = $metadata;
    }
    
    function set_delimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }
    
    function set_title($str)
    {
        $this->title = array();
        $this->title[] = $str;
    }
    
    function add_title($str)
    {
        $this->{$this->add_method . '_title'}($str);
    }
    
    function replace_last_title_segment($str)
    {
        $this->title[0] = $str;
    }
    
    function append_title($str)
    {
        array_push($this->title, $str);
    }
    
    function prepend_title($str)
    {
        array_unshift($this->title, $str);
    }
    
    function site_title()
    {
        return implode($this->delimiter, $this->title);
    }
}