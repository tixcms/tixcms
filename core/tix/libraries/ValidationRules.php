<?php

namespace Tix;

class ValidationRules
{
    protected $form;
    
    function __construct($form)
    {
        $this->form = $form;
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}