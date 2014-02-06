<?php

namespace HTML;

class Blocks extends \Block\Items
{
    function items()
    {
        return array(
            'content'=>array(
                'name'=>'Текстовый блок',
                'class'=>'HTML::Block',
                'description'=>'Блок, в который можно добавить текст или HTML код.',
                'visible_if_module_not_installed'=>true
            )
        );
    }
}