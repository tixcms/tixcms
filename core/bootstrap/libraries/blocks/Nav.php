<?php

namespace Bootstrap\Blocks;

class Nav extends \Block
{
    public $view = 'bootstrap::blocks/nav';
    public $options = array(
        'dynamic'=>false,
        'attrs'=>array()
    );
    
    function data()
    {
        return array();
    }
}