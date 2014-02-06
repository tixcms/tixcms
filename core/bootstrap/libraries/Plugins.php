<?php

namespace Bootstrap;

class Plugins extends \Tix\Plugin
{
    /**
     * Вывод файлов
     */
    function all($render = 'выводить сразу или добавлять в кучу')
    {
        $render = $this->attribute('render') == 'true';
        
        return \Bootstrap::all($render);
    }
    
    function css($render = '')
    {
        $render = $this->attribute('render') == 'true';
        
        return \Bootstrap::css($render);
    }
    
    function js($render = '')
    {
        $render = $this->attribute('render') == 'true';
        
        return \Bootstrap::js($render);
    }
}