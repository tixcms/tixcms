<?php

namespace Pages;

class Blocks extends \Block\Items
{
    function items()
    {
        return array(
            'page'=>array(
                'name'=>'Страница',
                'class'=>'Pages::Page',
                'description'=>''
            )
        );
    }
}