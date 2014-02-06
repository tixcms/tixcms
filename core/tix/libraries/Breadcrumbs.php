<?php

namespace Tix;

use URL;

class Breadcrumbs
{
    const MODULE = 1;
    const CONTROLLER = 2;
    const ACTION = 3;
    
    private $index_item = array('Home', '');
    private $items = array();
    private $delimiter = ' / ';
    private $item_before = '';
    private $item_after = '';
    private $show_on_index = false;
    private $start_tag;
    private $end_tag;
    private $url_level = false;
    
    private $ci;
    
    function __construct($config = array())
    {
        if( !$config )
        {
            $config = \CI::$APP->config->item('breadcrumbs');
        }
        
        $this->configure($config);
        $this->ci = \CI::$APP;
    }
    
    function add($title, $url = '')
    {
        if( is_array($title) )
        {
            foreach($title as $key => $value)
            {
                $this->add_item($key, $value);
            }
            
            return;
        }
        
        if( $url == '' )
        {
            switch(count($this->items))
            {
                case 1: $this->url_level = self::MODULE; break;
                case 2: $this->url_level = self::CONTROLLER; break;
                case 3: $this->url_level = self::ACTION; break;
            }
        }
        
        if( $this->url_level )
        {          
            $url = ($this->ci->is_backend ? 'admin' : '') . $url;
            
            if( $this->url_level >= self::MODULE )
            {
                $url .= ($this->ci->page->is_main ? '' : '/'. $this->ci->page->url);
            }
            
            if( $this->url_level >= self::CONTROLLER )
            {
                $url .= '/'. $this->ci->controller;
            }
            
            if( $this->url_level >= self::ACTION )
            {
                $url .= '/'. $this->ci->action;
            }
            
            $this->url_level = FALSE;
        }
        
        $this->items[] = array('title' => $title, 'url' => $url);
    }

    function set_delimiter($char)
    {
        $this->delimiter = $char;
    }

    function inactive_item_decoration($before = '', $after = '')
    {
        $this->item_before = $before;
        $this->item_after = $after;
    }

    function remove_last_item()
    {
        array_pop($this->items);
    }

    function render()
    {
        $temp = array();
        $total = count($this->items);

        if( $total == 1 OR ($this->show_on_index == false AND $this->ci->uri->segment(1) == '') )
        {
            return;
        }

        $result = $this->start_tag;
        for($i=0; $i<$total; $i++)
        {
            if($i+1 == $total)
            {
                $temp[] = $this->item_before.$this->items[$i]['title'].$this->item_after;
            }
            else
            {
                $temp[] = $this->item_before.URL::anchor($this->items[$i]['url'], $this->items[$i]['title']).$this->item_after;
            }
        }
        
        $result .= implode($this->delimiter, $temp);
        $result .= $this->end_tag;

        return $result;
    }
    
    function configure($config)
    {        
        if( isset($config['item_before']) )
        {
            $this->item_before = $config['item_before'];
        }
        
        if( isset($config['item_after']) )
        {
            $this->item_after = $config['item_after'];
        }
        
        if( isset($config['start_tag']) )
        {
            $this->start_tag = $config['start_tag'];
        }
        
        if( isset($config['end_tag']) )
        {
            $this->end_tag = $config['end_tag'];
        }
        
        if( isset($config['delimiter']) )
        {
            $this->delimiter = $config['delimiter'];
        }
        
        if( isset($config['index_item']) )
        {
            array_unshift(
                $this->items, 
                array(
                    'title'=>str_replace('{url}', \CI::$APP->di->url->site_url(), $config['index_item'][0]), 
                    'url'=>$config['index_item'][1]
                )
            );
        }
        
        $this->show_on_index = isset($config['show_on_index']) ? $config['show_on_index'] : $this->show_on_index;
    }
}