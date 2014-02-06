<?php

namespace Form\Input\File;

class Doc extends \Form\Input\File
{
    public $view = 'file/doc';
    
    function init()
    {
        parent::init();
        
        $this->value = $this->form->entity->{$this->field};
    }
}