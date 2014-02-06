<?php

namespace Tix\Controllers;

use CI;

class Base extends \MY_Controller
{
    public $is_backend = false;

    function __construct()
    {
        parent::__construct();
        
        if( !$this->is_system_installed() )
        {
            \URL::redirect('install');
        }
        
        \CI::$APP->di = new \Tix\DI();
        
        // флаг админка или публичная часть
        CI::$APP->is_backend = $this->is_backend;
        
        // загружаем библиотеки
        $this->load_libraries();
        
        $this->auth->init();
        
        $this->permissions->init();
        
        // включаем профайлер в режиме разработки
        if( ENVIRONMENT == 'development' )
        {
            $this->load->helper('tix/debug');
            
            if( !$this->is_ajax() )
            {
                $this->output->enable_profiler();
            }
        }
        
        $this->load->helper('language');
    }
        
    /**
     * Загрузка библиотек
     */    
    function load_libraries()
    {
        $this->load->database();
  
        $this->load->library('user_agent');
        $this->load->library('session');
        
        $this->load->library('Settings\Items', '', 'settings');  
        $this->load->library('Users\Auth', '', 'auth');
        $this->load->library('Users\Permissions', '', 'permissions');
        $this->load->library('Tix\Template', '', 'template');
    }
    
    function css($file, $attrs = array())
    {
        return $this->di->assets->css($file, $attrs);
    }
    
    function js($file, $attrs = array())
    {
        return $this->di->assets->js($file, $attrs);
    }
        
    /**
     * Сокращение для Template::render()
     */
    function render($view = false, $data = array())
    {
        $this->template->render($view, $data);
    }
    
    function set($key, $value)
    {
        $this->template->set($key, $value);
    }
    
    function crumb($name, $url = false)
    {
        $this->di->breadcrumbs->add($name, $url);
    }
    
    /**
     * Сокращение для проверки типа запроса
     */
    function is_ajax()
    {
        return $this->input->is_ajax_request();
    }
    
    function referer()
    {
        $this->di->url->referer();
    }
    
    function redirect($url = '', $extra = '')
    {
        $this->di->url->redirect($url, $extra);
    }
    
    function forward()
    {
        $params = func_get_args();
        
        echo call_user_func_array('Modules::run', $params);
    }
    
    function alert($type, $message, $name = false)
    {
        $this->di->alert->set($type, $message, $name);
    }
    
    function alert_flash($type, $message, $name = false)
    {
        $this->di->alert->set_flash($type, $message, $name);
    }
    
    function title($str)
    {
        $this->di->seo->add_title($str);
    }
    
    function description($str)
    {
        $this->di->seo->set_description($str);
    }
    
    function keywords($str)
    {
        $this->di->seo->set_keywords($str);
    }
    
    function index()
    {        
        show_404();
    }
    
    private function is_system_installed()
    {
        return file_exists(APPPATH .'config/installed');
    }
}