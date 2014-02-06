<?php

namespace Bootstrap\Blocks\Nav;

class Pills extends \Bootstrap\Blocks\Nav
{    
    function data()
    {
        $this->options['attrs']['class'] = 'nav nav-pills';
        
        return array();
    }
}