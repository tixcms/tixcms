<?php

namespace Bootstrap\Blocks\Modal;

class Form extends \Bootstrap\Blocks\Modal
{
    function data()
    {
        $this->options['attrs']['class'] = $this->options['attrs']['class']. ' '. $this->options['form']->attrs['class'];
        
        unset($this->options['form']->attrs['class']);
    }
}