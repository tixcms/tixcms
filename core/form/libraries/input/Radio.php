<?php

namespace Form\Input;

class Radio extends \Form\Input
{
    public $default_value = true;
    public $value = null;
    public $options = array();
    public $error = 'Поле %s обязательно';
    
    function init()
    {
        parent::init();
        
        if( $this->form->is_insert() AND !$this->form->submitted() AND is_null($this->value) )
        {
            $this->value = $this->default_value;
        }
        elseif( $this->form->submitted() )
        {
            $this->value = $this->form->post($this->field);
        }
    }
    
    function validate($str)
    {        
        if( array_key_exists($str, $this->options) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}