<?php

namespace Bootstrap\Blocks\Nav;

class NavList extends \Bootstrap\Blocks\Nav
{
    public $view = 'bootstrap::blocks/nav/navlist';
    
    function data()
    {
        $this->options['attrs']['class'] = 'nav nav-list';
        
        return array();
    }
}