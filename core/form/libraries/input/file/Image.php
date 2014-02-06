<?php

namespace Form\Input\File;

class Image extends \Form\Input\File 
{
    /**
     * Лейбл поля
     */
    public $label = 'Изображение';
    
    public $default_config = array(
        'upload_path'=>'uploads/images/',
        'allowed_types'=>'gif|jpg|png',
        'translit'=>true,
        'delete_old'=>true
    );
}