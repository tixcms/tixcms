<?php

namespace Block\Controllers;

class Backend extends \Admin\Controller 
{
    public $has_help = TRUE;
    
    function __construct()
    {
        parent::__construct();
        
        // модели
        $this->load->model('block/block_m');
        $this->load->model('block/block_areas_m');
        
        // скрипты
        $this->di->assets->js('block.js');
        $this->di->assets->js('jquery::ui/sortable.min.js');
        $this->di->assets->css('block.css');
    }
}