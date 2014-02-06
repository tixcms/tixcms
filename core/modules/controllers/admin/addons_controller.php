<?php

class Addons_Controller extends Admin\Controller
{    
    function __construct()
    {
        parent::__construct();
        
        // библиотека
        $this->load->library('Modules\Addons', '', 'addons');
        
        $this->load->model('modules_m');
    }
    
    function action_index()
    {
        $folders = glob('addons/*');
        $addons_in_folder = array();
        
        foreach($folders as $folder)
        {
            $file = glob($folder .'/*.php');
            
            if( isset($file[0]) )
            {
                $file = $file[0];
                
                include($file);
                
                $class = str_replace('.php', '', basename($file));
                $inst = new $class;
                
                $addons_in_folder[basename(dirname($file))] = array(
                    'folder'=>basename(dirname($file)),
                    'name'=>$inst->name,
                    'description'=>$inst->description,
                    'version'=>$inst->version,
                    'need_install'=>TRUE
                );
            }
        }
        
        $addons = array();
        $i=0;
        
        foreach($this->addons as $folder => $addon)
        {
            $addons[$i] = $addon;
            
            if( isset($addons_in_folder[$folder]) )
            {
                if( $addons_in_folder[$folder]['version'] != $addon['version'] )
                {
                    $addons[$i]['version'] = $addon['version'];
                    $addons[$i]['new_version'] = $addons_in_folder[$folder]['version'];
                    $addons[$i]['need_update'] = TRUE;
                }
                
                unset($addons_in_folder[$folder]);
            }
            else
            {
                $addons[$i]['need_install'] = FALSE;
            }
            
            $i++;
        }
        
        $addons = array_merge($addons_in_folder, $addons);
        
        //var_dump($addons);
        
        $this->render(array(
            'addons'=>$addons
        ));
    }
    
    /**
     * Установка дополнения
     */
    function action_install($addon)
    {
        if( $addon = $this->addons->get_addon($addon) )
        {
            $addon->install();
            $addon->default_install();
            $addon->update(0, $addon->version);
            
            $this->di->alert->set_flash('success', 'Дополнение успешно установлено');
        }
        else
        {
            $this->di->alert->set_flash('error', 'Ошибка');
        }
        
        $this->referer();
    }
    
    /**
     * Удаление дополнения
     */
    function action_uninstall($addon)
    {
        if( $addon == 'app' )
        {
            $this->redirect('admin/modules');
        }
        
        if( $addon = $this->addons->get_addon($addon) )
        {
            $addon->default_uninstall();
            $addon->uninstall();
            
            $this->di->alert->set_flash('success', 'Дополнение успешно удалено');
        }
        else
        {
            $this->di->alert->set_flash('error', 'Не возможно выполнить удаление, не был найден установочный файл дополнения');
        }
        
        $this->referer();
    }
    
    /**
     * Обновление модуля
     */
    function action_update($addon)
    {
        $addon_installed = $this->modules_m->by_url($addon)->get_one();
        
        if( $addon = $this->addons->get_addon($addon) )
        {
            $addon->update($addon_installed->version, $addon->version);
            
            $this->modules_m->by_url($addon->url)->set_version($addon->version)->update();
            
            $this->di->alert->set_flash('success', 'Дополнение успешно обновлено');
        }
        else
        {
            $this->di->alert->set_flash('error', 'Ошибка');
        }
        
        $this->referer();
    }
    
    private function success_return($str)
    {
        if( $this->input->is_ajax_request() )
        {
            echo json_encode(array(
                'success'=>TRUE,
                'message'=>$str
            ));
        }
        else
        {
            $this->alert_flash('success', $str);
            
            $this->referer();
        }
    }
    
    /**
     * Проверяем версию php и mysql
     */
    function check_requirements()
    {
        if( !$this->install->php_acceptable('5.3') )
        {
            $this->errors[] = 'Для работы TixCMS требуется версия php не ниже 5.3';
            return FALSE;
        }
        
        return TRUE;
    }
    
    function cms_installed()
    {
        return file_exists(APPPATH .'config/installed');
    }
    
    function errors()
    {
        return implode('<br />', $this->errors);
    }
}