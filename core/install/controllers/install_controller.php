<?php

class Install_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        
        if( file_exists(APPPATH .'config/installed') )
        {
            show_404();
        }
        
        $this->load->config('app/app');
        
        CI::$APP->module = 'install';
        CI::$APP->controller = 'install';
        CI::$APP->action = $this->router->fetch_method();
        CI::$APP->is_backend = FALSE;
        
        CI::$APP->di = new Tix\DI;
        $this->di->set($this->config->item('services'));
        
        $this->load->library('Tix\Template', '', 'template');
        $this->template->engines = $this->config->item('template_engines');
        $this->template->add_layout(array('layouts/default', 'layout'));
        
        $this->load->library('session', array(
            'sess_use_database'=>FALSE
        ));
        $this->load->library('Install', '', 'install');
        
        $this->di->set('url', 'Helpers\URL');
        $this->di->set('assets', 'Tix\Assets');        
        $this->di->set('alert', 'Tix\Alert');
        
        $this->template->set_theme('install');
    }
    
    function action_index()
    {
        URL::redirect('install/step1');
    }
    
    function action_step1()
    {
        $error = FALSE;
        
        $items = array(
            array(
                'path'=>APPPATH .'config',
                'value'=>FALSE
            ),
            array(
                'path'=>'uploads',
                'value'=>FALSE
            )
        );
        
        for($i=0; $i<count($items); $i++)
        {
            if( is_really_writable($items[$i]['path']) )
            {
                $items[$i]['value'] = TRUE;
            }
            else
            {
                $error = TRUE;
            }
        }
        
        if( !$error )
        {
            $this->session->set_userdata('step1', 1);
        }
        
        $this->template->render('step1', array(
            'items'=>$items,
            'error'=>$error
        ));
    }
    
    function action_final()
    {
        if( !$this->session->userdata('step1') )
        {
            URL::redirect('install/step1');
        }
        
        if( !$this->session->userdata('step2') )
        {
            URL::redirect('install/step2');
        }
        
        if( !$this->session->userdata('step3') )
        {
            URL::redirect('install/step3');
        }
        
        if( !$this->session->userdata('final') )
        {
            URL::redirect('install/step3');
        }
        
        file_put_contents(APPPATH .'config/installed', '');
        
        $this->session->unset_userdata('step1');
        $this->session->unset_userdata('step2');
        $this->session->unset_userdata('step3');
        $this->session->unset_userdata('final');
        
        $this->template->render('final');
    }
    
    function action_step3()
    {        
        if( $this->session->userdata('final') )
        {
            URL::redirect('install/final');
        }
        
        if( !$this->session->userdata('step2') )
        {
            URL::redirect('install/step2');
        }
        
        if( !$this->session->userdata('step3') )
        {
            $this->load->database();
                        
            $this->insert_data();
            
            $this->session->set_userdata('step3', 1);
        }
        
        $form = new Install\Forms\User;
        
        if( $form->submitted() )
        {
            if( $form->validate() )
            {                
                $form->save();
                
                $this->session->set_userdata('final', 1);
                
                URL::redirect('install/final');
            }   
            else
            {
                $this->di->alert->set('error', $form->get_errors());
            }
        }
        
        $this->template->render('step3', array(
            'form'=>$form
        ));
    }
    
    function action_step2()
    {
        if( $this->session->userdata('step1') != 1 )
        {
            URL::redirect('install/step1');
        }   
        
        $form = new Install\Forms\DB;
        
        if( $form->submitted() )
        {
            if( $form->validate() )
            {                
                $data = array(
                    'hostname'=>$form->get('hostname'),
                    'username'=>$form->get('username'),
                    'password'=>$form->get('password'),
                    'database'=>$form->get('database'),
                    'port'=>$form->get('port'),
                    'prefix'=>$form->get('prefix')
                );
                
                if( $this->install->mysql_acceptable($data) )
                {
                    $this->create_configs($data);
                    
                    if( $this->create_database($data) )
                    {
                        $this->session->set_userdata('step2', 1);
                        
                        URL::redirect('install/step3');
                    }
                    else
                    {
                        $this->di->alert->set('error', 'Нет прав для создания базы данных. Создайте вручную.');
                    }                    
                }
                else
                {
                    $this->di->alert->set('error', 'Невозможно соединиться с базой данных. Проверьте введенные данные');
                }
            }
            else
            {
                $this->di->alert->set('error', $form->get_errors());
            }
        }
        
        $this->template->render('step2', array(
            'form'=>$form
        ));
    }
    
    private function insert_data()
    {
        $this->load->library('Modules\Addons', '', 'addons');
        
        $core_version = $this->addons->get_new_core_version();
        
        $folders = array_merge(glob('core/*'), glob('addons/*'), glob('themes/*'));
        
        foreach($folders as $folder)
        {
            $files = glob($folder .'/*.php');
            
            if( count($files) )
            {
                list($file) = $files;
                
                if( file_exists($file) )
                {
                    include($file);
                    
                    $class = str_replace('.php', '', basename($file));
                    
                    if( class_exists($class) )
                    {
                        $instance = new $class;
                        
                        // модули в папке дополнений устанавливаются по своим версиям
                        if( strstr($file, 'core') !== FALSE )
                        {
                            $instance->version = $core_version;
                        }
                        
                        $items['queue'][$instance->url] = $instance;
                    }
                }
            }
        }
        
        $modules_installation_order = array('modules', 'settings', 'email', 'users', 'block', 'categories', 'dashboard', 'form', 'pages', 'security', 'nav'); 
        
        $i=1;
        foreach($modules_installation_order as $module)
        {       
            $items['queue'][$module]->install();
            $items['queue'][$module]->default_install();
            $items['queue'][$module]->update(0, $items['queue'][$module]->version);
            
            unset($items['queue'][$module]);
            
            $i++;
        }
        
        $items['installed'] = array();
        $items['wait'] = array();
        foreach($items['queue'] as $item)
        {
            $required_modules = $item->requires();
        
            if( !$required_modules OR $this->required_modules_installed($required_modules, $items['installed']) )
            {          
                $item->install();
                $item->default_install();
                $item->update(0, $item->version);
                
                $items['installed'][] = $item->url;
            }
            else
            {
                $items['wait'][$item->url] = $item;
            }
            
            if( $items['wait'] )
            {
                foreach($items['wait'] as $key=>$item_wait)
                {
                    $required_modules = $item_wait->requires();
                    
                    if( !$required_modules 
                            OR $this->required_modules_installed($required_modules, $items['installed']) )
                    {
                        $item_wait->install();
                        $item_wait->default_install();
                        $item_wait->update(0, $item_wait->version);
                        
                        unset($items['wait'][$key]);
                        $items['installed'][] = $key;
                    }
                }
            }
        }
        
        $this->db->set('version', $core_version)->insert('core_version');
    }
    
    function required_modules_installed($required, $installed)
    {
        foreach($required as $item)
        {
            if( !in_array($item, $installed) )
            {
                return FALSE;
            }
        }
        
        return TRUE;
    }
    
    /**
     * Создание базы данных
     */
    private function create_database($data)
    {
        $db = @mysql_connect($data['hostname'], $data['username'], $data['password']);
        
        if( mysql_select_db($data['database'], $db) )
        {
            return TRUE;
        }
        
        mysql_query('CREATE DATABASE IF NOT EXISTS '. $data['database'], $db);
        
        if( !mysql_select_db($data['database'], $db) )
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * Создание конфигов
     */
    private function create_configs($data)
    {
        $this->load->library('Tix\Parser', '', 'parser');
        
        $data['db_debug'] = 'FALSE';
        $db_template = $this->template->view('db_config_template');
        $template = $this->parser->parse($db_template, $data);

        file_put_contents(APPPATH .'config/database.php', $template);
        
        Helpers\File::make_path(APPPATH .'config/production');
        file_put_contents(APPPATH .'config/production/database.php', $template);
        
        Helpers\File::make_path(APPPATH .'config/development');
        $data['db_debug'] = 'TRUE';
        $template = $db_template = $this->template->view('db_config_template');
        $template = $this->parser->parse($template, $data);
        file_put_contents(APPPATH .'config/development/database.php', $template);
        
        $template = $this->template->view('dev_config_template');
        file_put_contents(APPPATH .'config/development/config.php', $template);
    }
}