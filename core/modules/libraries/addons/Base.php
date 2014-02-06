<?php

namespace Modules\Addons;

class Base 
{
    protected $module_id;
    protected $module;
    protected $module_inst;
    
    const ADDONS_URL = 'http://tixcms.ru/addons/get/';
    const UPLOAD_PATH = 'uploads/';
    const EXTRACT_PATH = 'modules/';
    
    /**
     * Конструктор
     * @param id ID модуля
     */
    function __construct($id)
    {
        $this->module_id = $id;
    }
    
    /**
     * Скачивание модуля
     */
    protected function download()
    {
        $archive = file_get_contents(self::ADDONS_URL . 'download/' . $this->module_id);
        $this->module = unserialize(file_get_contents(self::ADDONS_URL . 'info/'. $this->module_id));
        
        if( !is_writable(self::UPLOAD_PATH) )
        {
            echo json_encode(array(
                'success'=>false,
                'message'=>'Для установки модуля требуются права для записи в папку '. self::UPLOAD_PATH
            ));
            
            exit;
        }
        
        file_put_contents(self::UPLOAD_PATH . $this->module->archive, $archive);
    }
    
    /**
     * Распаковка модуля
     */    
    protected function unzip()
    {
        $zip = new \ZipArchive;
        
        // скачанный архив
        $file = self::UPLOAD_PATH . $this->module->archive;
        
        $res = $zip->open($file);
        
        if ($res === TRUE)
        {            
            $zip->extractTo(rtrim(self::EXTRACT_PATH, '/'));
            $zip->close();
            
            unlink($file);
            
            return TRUE;
        } 
        else 
        {
            return FALSE;
        }
    }
    
    /**
     * Удаление файлов
     */
    protected function delete_files()
    {
        $fileHelper = new \Helpers\File;
        
        $files = $this->module_inst->files();
        
        $folder = $this->get_subfolder();
        $folder = str_replace('..', '', $folder);
        
        if( $folder != '' )
        {
            // если указаны файлы, удаляем их
            if( count($files) > 0)
            {
                foreach($files as $file)
                {
                    $file = self::EXTRACT_PATH . $folder .'/'. str_replace('..', '', $file);
                    
                    $fileHelper->remove($file);
                }
            }
            // если не указаны файлы удаляем всю папку
            else
            {
                $fileHelper->remove(self::EXTRACT_PATH . $folder);
            }
        }
    }
    
    /**
     * Подмодуль
     */
    protected function get_subfolder()
    {
        $folder = '';
        if( strstr($this->module->class, '\\') !== FALSE )
        {
            list($folder) = explode('\\', strtolower($this->module->class));
        }
        else
        {
            $folder = strtolower($this->module->class);
        }
        
        return $folder;
    }
    
    /**
     * Создает и возвращает класс модуля
     */
    function get_module_instance()
    {
        $module_slug = strtolower($this->module->class);
        
        if( strstr($module_slug,'\\') !== FALSE )
        {
            $parts = explode('\\', $module_slug);
            $class = $parts[count($parts) - 1];
            $path = self::EXTRACT_PATH . str_replace('\\', DIRECTORY_SEPARATOR, $module_slug) . EXT;
        }
        else
        {
            $class = $module_slug;
            $path = self::EXTRACT_PATH . $module_slug .'/'. $module_slug . EXT;
        }
        
        include($path);
        
        return new $class;
    }
}