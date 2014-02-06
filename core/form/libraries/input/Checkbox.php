<?php

namespace Form\Input;

class Checkbox extends \Form\Input
{
    // по-умолчанию
    public $default_value = TRUE;
    public $value = NULL;
    
    function init()
    {
        parent::init();
        
        if( !$this->view )
        {
            $this->view = $this->form->inputs_folder . 'checkbox';
        }

        // по-умолчания чекбокс активен
        if( $this->form->is_insert() AND !$this->form->submitted() AND is_null($this->value) )
        {
            $this->value = $this->default_value;
        }
        elseif( $this->form->submitted() )
        {
            $this->value = set_value($this->field) == 'on';
        }
    }
    
    function validate()
    {
        $this->form->set($this->field, (int)($this->form->get($this->field) == 'on'));
        
        return TRUE;
    }
}