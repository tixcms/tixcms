<?php

namespace HTML\Blocks;

class Block extends \Block
{    
    function data(){}
    
    function render()
    {
        return nl2br($this->options['html']);
    }
}