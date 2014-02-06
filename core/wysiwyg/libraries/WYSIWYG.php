<?php

class WYSIWYG 
{    
    public $wysiwyg;
    
    private function __construct()
    {
        // загружаем визивиг из текущего модуля
        $this->load->config($this->module->url .'/wysiwyg', false, true);
        
        $this->wysiwyg = $this->config->item('wysiwyg_instance');
    }
    
    function run()
    {
        if( !$this->wysiwyg )
        {
            return;
        }
        
        return $this->wysiwyg->run();
    }
    
    /**
     * Инициализация визивига
     * 
     * @param boolen Загружать или нет
     */
    static function init($run)
    {
        if( !$run )
        {
            return;
        }
        
        $inst = new self();
            
        return $inst->run();
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}