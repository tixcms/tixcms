<?php

class Core_Controller extends Admin\Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('modules_m');
        
        // библиотека
        $this->load->library('Modules\Addons', '', 'addons');
    }
    
    function action_update()
    {
        $new_core_version = $this->addons->get_new_core_version();
        $core_version = $this->db->get('core_version')->row()->version;
        
        if( $core_version == $new_core_version )
        {
            $this->alert_flash('attention', 'Обновления не требуется');
            
            $this->referer();
        }
        
        $folders = glob('core/*');
        
        foreach($folders as $folder)
        {
            $files = glob($folder .'/*.php');
            
            if( count($files) )
            {
                $file = $files[0];
                
                include($file);
                
                $class = str_replace('.php', '', basename($file));
                
                if( class_exists($class) )
                {
                    $instance = new $class;
                    
                    $instance->update($core_version, $new_core_version);
                }
            }
        }
        
        $this->db->set('version', $new_core_version)->update('core_version');
        
        $this->alert_flash('success', 'Система успешно обновлена до версии '. $new_core_version);
        
        $this->referer();
    }
}