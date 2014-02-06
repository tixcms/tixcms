<?php

namespace Modules;

class Addons
{
    public $addons_folder = 'addons/';
    
    /**
     * Версия ядра в папке
     */
    function get_new_core_version()
    {
        return file_get_contents('core/version');
    }
    
    /**
     * Неустновленные модули
     */
    function get_uninstalled($installed, $all)
    {
        if( $installed )
        {
            foreach($installed as $addon)
            {
                if( isset($all[$addon->url]) )
                {
                    unset($all[$addon->url]);
                }
            }
        }
        
        unset($all['app']);
        
        return $all;
    }
    
    function get_addon($addon)
    {
        // ищем в дополнениях
        if( !$file = glob($this->addons_folder . $addon.'/*.php') )
        {
            $file = glob('themes/' . $addon .'/*.php');
        }
        
        if( !isset($file[0]) )
        {
            return false;
        }
        
        $file = $file[0];
        
        include($file);
        
        $class = str_replace('.php', '', basename($file));
        
        return class_exists($class) ? new $class : FALSE;
    }
    
    /**
     * Модули находящиеся в папке
     */
    function get_in_folder()
    {
        $folders = array_merge(glob($this->addons_folder . '*'), glob('themes/*'));
        $addons_in_folder = array();
        
        foreach($folders as $folder)
        {
            $file = glob($folder .'/*.php');
            
            if( isset($file[0]) )
            {
                $file = $file[0];
                
                include($file);
                
                $class = str_replace('.php', '', basename($file));
                
                if( class_exists($class) )
                {
                    $inst = new \Modules\Entity(array(new $class));                   
                
                    $addons_in_folder[basename(dirname($file))] = $inst;
                }
            }
        }
        
        return $addons_in_folder;
    }
}