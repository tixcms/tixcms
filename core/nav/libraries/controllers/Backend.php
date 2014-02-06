<?php

namespace Nav\Controllers;

class Backend extends \Admin\Controller 
{
    public $has_help = TRUE;
    
    function __construct()
    {
        parent::__construct();
        
        // модели
        $this->load->model('nav_m');
        $this->load->model('nav_areas_m');
        
        // заголовок
        $this->di->seo->add_title('Навигация');
        
        // хлебные крошки
        $this->crumb('Навигация', 'admin/nav');
        
        // скрипты
        $this->di->assets->js('nav.js', '', '1');
        $this->di->assets->js('jquery::ui/sortable.min.js');
        
        $this->di->assets->css('admin.css');
    }
}