<?php

namespace Modules\Addons;

class Uninstall extends Base
{
    function __construct($id)
    {
        parent::__construct($id);
        
        $this->module = \CI::$APP->modules_m->by_id($id)->get_one();
    }
    
    /**
     * Запуска установки
     */
    function run()
    {
        // удаление
        $this->module_inst = $this->get_module_instance();
        $this->module_inst->uninstall();
        
        // удаляем файлы
        $this->delete_files();
    }
}