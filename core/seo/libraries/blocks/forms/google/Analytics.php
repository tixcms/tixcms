<?php

namespace SEO\Blocks\Forms\Google;

class Analytics extends \Block\Form
{
    function data()
    {
        return array(
            'tracking_ID'=>array(
                'type'=>'text',
                'label'=>'Tracking ID',
                'save'=>false,
                'rules'=>'trim|required',
                'help'=>'ID сайта (UA-1234567-1)'
            )
        );
    }
}