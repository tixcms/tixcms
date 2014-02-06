<?php

namespace Theme;

class Settings extends \Settings\Form
{    
    const UPLOAD_PATH = 'uploads/images/';
    
    function inputs()
    {
        return array(
            'theme_logo'=>new \Form\Input\File\Image\Simple(array(
                'config'=>array(
                    'upload_path'=>self::UPLOAD_PATH
                ),
                'label'=>'Логотип',
                'default'=>''
            )),
            'theme_sidebar'=>array(
                'type'=>'select',
                'label'=>'Расположение боковой колонки',
                'options'=>array(
                    'left'=>'Слева',
                    'right'=>'Справа'
                ),
                'default'=>'left'
            ),
            'theme_bg'=>$this->load->library('Theme\BgInput', array(
                'label'=>'Задний фон',
                'folder'=>'bg',
                'default'=>'climpek'
            )),
            'theme_bg_footer'=>$this->load->library('Theme\BgInput', array(
                'label'=>'Задний фон подвала',
                'folder'=>'bg/footer',
                'default'=>'binding_dark'
            )),
        );
    }
}