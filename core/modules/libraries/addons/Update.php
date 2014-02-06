<?php

namespace Modules\Addons;

class Update extends Base
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
    
        // обновляем
        $module = $this->get_module_instance();
        $module->update();
    }
}