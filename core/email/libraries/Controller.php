<?php

namespace Email;

class Controller extends \Admin\Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->template->add_layout('layout');
    }
}