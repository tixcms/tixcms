<?php

namespace Tix;

use CI;

class Pagination 
{
    private $view = 'app::pagination';
    private $total;
    private $per_page;
    private $url;
    private $query;
    private $page;
    private $generate_url_with_query = true;
    
    function __construct($params = array())
    {
        foreach($params as $key=>$value)
        {
            $this->$key = $value;
        }
    }
    
    function render()
    {        
        if( $this->total > $this->per_page )
        {
            return CI::$APP->template->view($this->view, array(
                'pages_total'=>ceil($this->total/$this->per_page),
                'current_page'=>$this->page,
                'link'=>$this->url,
                'query'=>$this->query,
                'pager'=>$this
            ));
        }
    }
    
    /**
     * Создает ссылку. Или чпу или query
     */
    function url($page, $text, $attrs = '')
    {
        if( is_object($this->query) AND $this->generate_url_with_query )
        {
            return $this->di->url->anchor($this->url, $text, $attrs, $this->url_query($page > 1 ? $page : false));
        }
        else
        {
            return $this->di->url->anchor($this->url . ($page != 1 ? '/'. $page : ''), $text, $attrs);
        }
    }
    
    function url_query($page)
    {        
        if( $page )
        {
            $this->query->set('page', $page);
            
            return '?' . $this->query->generateUriQuery();
        }
        else
        {
            return '?' . $this->query->generateUriQuery('', '', array('page'));
        }
    }

    function set_view($view)
    {
        $this->view = $view;
        
        return $this;
    }

    function set_per_page($per_page)
    {
        $this->per_page = $per_page;
        
        return $this;
    }

    function set_total($total)
    {
        $this->total = $total;
        
        return $this;
    }

    function set_url($url, $query = false)
    {
        $this->url = $url;
        if( !is_object($query) )
        {
            $this->query = $query ? '?'. $query : '';
        }
        else
        {
            $this->query = $query;
        }
        return $this;
    }

    function set_page($page)
    {
        $this->page = $page;
        return $this;
    }
    
    function set_generate_url_with_query($val)
    {
        $this->generate_url_with_query = $val;
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}