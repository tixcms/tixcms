<?php

namespace Bootstrap\Blocks;

class Alert extends \Block
{
    public $options = array(
        'type'=>'success',
        'text'=>'default text',
        'show_close'=>true
    );   
    
    function data(){}
}