<?php

$config = array(
    'wysiwyg_instance' => false,

    'template_engines'=>array(
        'default'=>new \Tix\Template\Native
    ),
    'cache_path'=>APPPATH .'../cache/',
    'services'=>array(),
    'helpers'=>array('language'),
    'language'=>'ru'
);