<?php

namespace Tix\Controllers;

class App extends Base
{
    function __construct()
    {
        parent::__construct();
        
        // main config
        $this->load->config('app/app');

        $this->add_services();
        
        // проверяем на завершающий слеш
        if( $this->notHasTrailingSlash() )
        {
            return $this->redirectToUrlWithTrailingSlash();
        }
        
        // перенаправляем на главную со следующих адресов
        if( stripos($_SERVER['REQUEST_URI'], '/index.php') !== FALSE )
        {
            $this->url->redirect('', '', '', 301);
        }

        if( $this->settings->frontend_enabled != 1 )
        {
            show_error($this->settings->unavailable_message);
        }
        
        if( !$this->module_available() )
        {
            show_404();
        }
        
        if( !$this->page_has_access() )
        {
            $this->show_error(lang('Доступ запрещен'), lang('Доступ к данной странице закрыт'), '', 403);
        }

        $this->load->config($this->config->item('theme').'/theme', false, true);
    
        $this->template->engines = $this->config->item('template_engines');
        $this->template->set_theme($this->config->item('theme'));
        $this->template->add_layout($this->config->item('layout'));
        
        $this->di->events->trigger('post_app');
    }
    
    function add_services()
    {
        $folders = array_merge(glob('core/tix'), glob('addons/*'), glob('themes/*'));
        
        $services = array();
        foreach($folders as $folder)
        {
            if( is_dir($folder) )
            {
                $module = basename($folder);
                
                $config = $this->config->load($module .'/services/app', false, true);
                
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
     * Проверяем, что урл заканчивается завершающим слешем
     */
    function notHasTrailingSlash()
    {
        if( $this->controller == 'errors' AND $this->action == '404' )
        {
            return false;
        }
        
        list($uri) = explode('?', $_SERVER['REQUEST_URI']);
        
        
        
        return substr($uri, -1) != '/';
    }
    
    /**
     * Редиректим на урл со слешем
     */
    function redirectToUrlWithTrailingSlash()
    {
        if( strstr($_SERVER['REQUEST_URI'], '?') !== false )
        {
            list($uri, $query) = explode('?', $_SERVER['REQUEST_URI']);
        }
        else
        {
            $uri = $_SERVER['REQUEST_URI'];
            $query = false;
        }

        $urlWithSlash = $this->url->site_url(ltrim($uri, '/')) . ($query ? '?'. $query : '');
        
        return $this->url->redirect($urlWithSlash, '', '', 301);
    }
    
    function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        echo \Modules::run('pages/errors/action_error', array(
            'heading'=>$heading,
            'message'=>$message, 
            'template'=>$template, 
            'status_code'=>$status_code
        ));
    }
    
    function page_has_access()
    {
        return $this->page->has_access();
    }
    
    function module_available()
    {
        $this->load->model('modules/modules_m');
        
        \CI::$APP->module = $this->modules_m->by_url($this->module)->get_one();
         
        \CI::$APP->page = $this->page = $this->load->library('Pages\Entity', array($this->router->page));

        return \CI::$APP->module OR $this->uri->segment(1) == 'admin';
    }
    
    /**
     * Устанавливает метаданные (title, description, keywords)
     * для страниц, к которым привязан модуль
     * 
     * Только для главной страницы модуля
     */
    function set_metadata()
    {
        $this->title($this->page->meta_title ? $this->page->meta_title : $this->page->title);
        $this->crumb($this->page->title);
        
        if( $this->page->meta_description )
        {
            $this->description($this->page->meta_description);
        }
        
        if( $this->page->meta_keywords )
        {
            $this->keywords($this->page->meta_keywords);
        }
    }
}