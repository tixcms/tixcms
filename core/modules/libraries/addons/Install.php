<?php

namespace Modules\Addons;

class Install extends Base
{    
    /**
     * Запуска установки
     */
    function run()
    {
        // загружаем файлы
        $this->download();
        
        // распаковываем
        $this->unzip();
    
        // устанавливаем
        $module = $this->get_module_instance();
        $module->install();
    }
}