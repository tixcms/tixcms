<?php

namespace Page\Blocks;

class Menu extends \Block
{
    function data()
    {
        \CI::$APP->load->model('page/page_m');
        
        $parent = \CI::$APP->page_m->by_url($this->options['url'])->get_one();
        
        $this->pages = FALSE;
        if( $parent )
        {
            $this->pages = \CI::$APP->page_m->by_parent_id($parent->id)->get_all();
        }        
    }
}