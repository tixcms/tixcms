<?php

namespace Block;

class Plugins extends \Tix\Plugin
{
    /**
     * Вывод блока
     */
    function inst($id = 'Идентификатор блока')
    {
        $id = $this->attribute('id');
        
        return \Block::inst($id);
    }
    
    /**
     * Вывод области блоков
     */
    function area($alias = 'Идентификатор области')
    {
        $alias = $this->attribute('alias');
        
        return \Block::area($alias);
    }
}