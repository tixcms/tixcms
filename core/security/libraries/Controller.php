<?php

namespace Security;

class Controller extends \Admin\Controller
{
    public $has_settings = true;
    
    function __construct()
    {
        parent::__construct();
        
        $this->template->add_layout('layout');
    }
    
    function action_settings($action = 'index')
    {
        $this->template->remove_layout();
        
        parent::action_settings($action);
    }
}