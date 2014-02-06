<?php

namespace Nav;

class Plugins extends \Tix\Plugin
{
    /**
     * Вывод области навигации
     */
    function area($alias = 'идентификатор области')
    {
        $alias = $this->attribute('alias');
        
        return \Block::view('Nav::Area', array(
            'area'=>$alias
        ));
    }
}