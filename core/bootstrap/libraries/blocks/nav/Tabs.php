<?php

namespace Bootstrap\Blocks\Nav;

class Tabs extends \Bootstrap\Blocks\Nav
{
    function data()
    {
        $this->options['attrs']['class'] = 'nav nav-tabs';
        
        return parent::data();
    }
}