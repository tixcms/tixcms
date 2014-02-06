<?php

namespace Modules;

class AbstractModule
{
    /**
     * Имя модуля
     */
    public $name;
    
    /**
     * Имя модуля
     */
    public $description;
    
    /**
     * Имя на англ.
     */
    public $class;   
    
    /**
     * Ссылка
     */
    public $url; 
    
    /**
     * Версия
     */
    public $version;
    
    /**
     * Является ли модуль утилитой
     */
    public $is_utility = 0;
    
    /**
     * Является ли вспомогательным модулем
     */
    public $is_helper = 0;
    
    /**
     * Относится ли модуль к ядру
     */
    public $is_core = 0;
    
    /**
     * Работает ли в публичной части сайта
     */
    public $is_frontend = 0;
    
    /**
     * Работает ли в административной части сайта
     */
    public $is_backend = 0;
    
    /**
     * Автор
     */
    public $author = '';
    
    function install()
    {
        $this->add_to_modules();
    }
    
    function uninstall()
    {        
        $this->modules_m->by_class($this->class)->delete();
        $this->settings_m->by_module($this->class)->delete();
    }
    
    function update()
    {        
        $this->modules_m->by_class($this->class)->delete();
        $this->add_to_modules();
    }
    
    protected function add_to_modules()
    {
        $this->modules_m->insert(array(
            'name'=>$this->name,
            'description'=>$this->description,
            'class'=>$this->class,
            'version'=>$this->version,
            'is_utility'=>$this->is_utility,
            'is_helper'=>$this->is_helper,
            'is_core'=>$this->is_core,
            'is_frontend'=>$this->is_frontend,
            'is_backend'=>$this->is_backend,
            'author'=>$this->author,
        ));
    }
    
    function files()
    {
        return array();
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}