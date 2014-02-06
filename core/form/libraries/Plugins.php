<?php

namespace Form;

class Plugins extends \Tix\Plugin
{
    function render($alias = 'Идентификатор')
    {
        $alias = $this->attribute('alias');
        
        return \Form\Generated::view($alias);
    }
}