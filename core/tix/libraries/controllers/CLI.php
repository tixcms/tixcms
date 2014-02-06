<?php

namespace Tix\Controllers;

class CLI extends \MY_Controller
{
    function __construct()
    {
        parent::__construct();
        
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        
        if( !$this->input->is_cli_request() )
        {
            show_404();
        }
    }
}