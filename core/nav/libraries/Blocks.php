<?php

namespace Nav;

class Blocks extends \Block\Items
{
    function items()
    {
        return array(
            'area'=>array(
                'name'=>'Навигационное меню',
                'class'=>'Nav::Area',
                'description'=>'Вывод навигационного меню'
            )
        );
    }
}