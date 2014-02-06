<?php

namespace Pages\Controllers;

class Backend extends \Admin\Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->template->add_layout('layout');
        
        $this->load->language('admin');
        
        $this->load->model('categories/categories_m');
        
        $this->di->assets->js('script.js');
    }
}