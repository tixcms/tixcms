<?php

namespace Admin;

use CI;

class Controller extends \Tix\Controllers\Base
{
    public $has_settings = false;
    public $only_settings = false; // есть в контроллере экшены кроме настроек
    public $has_categories = false;
    public $has_help = false;
    public $has_emails = false;
    
    function __construct()
    {
        parent::__construct();

        //  main config
        $this->load->config('app/admin');
        $this->add_services();
        
        $this->di->events->trigger('pre_admin_controller');
        
        $this->is_backend = CI::$APP->is_backend = true;

        // есть ли доступ у юзера в админку
        if( !$this->user->backend_access() OR !$this->user->from_backend() )
        {
            $this->redirect('admin?url='. $this->uri->uri_string());
        }
        
        // есть ли доступ к модулю
        if( $this->need_to_check() AND !$this->user->module_access() )
        {
            $this->alert->set_flash('attention', 'У вас нет доступа к этому модулю');
            
            $this->redirect('admin/dashboard');
        }
        
        \CI::$APP->module = $this->modules_m->by_url($this->module)->get_one();
        
        if( !$this->module )
        {
            show_404();
        }
        
        // тема и вложенный шаблон
        $this->template->engines = $this->config->item('template_engines');
        $this->template->set_theme('admin');
        $this->template->add_layout('admin::layouts/default');
        $this->template->parse = false;
        
        $this->template->set('has_settings', $this->has_settings);
        $this->template->set('only_settings', $this->only_settings);
        $this->template->set('has_categories', $this->has_categories);
        $this->template->set('has_help', $this->has_help);
        $this->template->set('has_emails', $this->has_emails);
        $this->template->add_layout('admin::_header');
        
        // версия ядра
        $version = $this->db->get('core_version')->row()->version;
        CI::$APP->version = $version;
        $this->template->set('core_version', $version);

        $this->user->settings = $this->user->settings ? unserialize($this->user->settings) : array();
        
        $this->config->set_item('language', $this->user->settings('admin_language'));
        $this->load->language('admin/admin');
        
        $this->di->events->trigger('post_admin_controller');
    }
    
    private function add_services()
    {
        $folders = array_merge(glob('core/tix'), glob('addons/*'), glob('themes/*'));
        
        $services = array();
        foreach($folders as $folder)
        {
            if( is_dir($folder) )
            {
                $module = basename($folder);
                
                $config = $this->config->load($module .'/services/admin', false, true);
                
                if( $this->config->item('services') )
                {
                    $services = array_merge($services, $this->config->item('services'));
                }
                
                $this->config->set_item('services', false);
                
            }
        }
        
        $this->di->set($services);
        
        $this->di->set($this->config->item('services'));
    }
    
    /**
     * Модули не записанные в таблицу, как WYSIWYG, не требуется проверять на доступ
     */
    private function need_to_check()
    {
        $this->load->model('modules/modules_m');
        return $this->modules_m->by_url($this->module)->count() > 0;
    }
    
    /**
     * Все модули могут иметь настройки. В модули не требуется создавать экшен settings
     * Работает по такому пути /admin/{module}/settings
     */
    function action_settings()
    {
        $controller = new \Settings\Controller;
        $controller->run();
    }
    
    /**
     * Категории для всех модулей
     * /admin/{module}/categories
     */
    function action_categories($action = 'index')
    {
        $cat = new \Categories($action);
        $cat->display();
    }
    
    function action_emails()
    {
        \Email\Templates::render();
    }
    
    function action_help()
    {
        \Admin\Help::render();
    }
}