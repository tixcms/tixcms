<?php

namespace Install\Forms;

class DB extends \Form
{
    public $actions_view = 'forms/actions';
    public $csrf_protection = false;
    
    public $attrs = array(
        'class'=>'form-horizontal'
    );
    
    function init()
    {
        parent::init();
        
        $this->inputs = array(
            'hostname'=>array(
                'type'=>'text',
                'label'=>'Имя хоста',
                'rules'=>'trim|required'
            ),
            'username'=>array(
                'type'=>'text',
                'label'=>'Имя пользователя',
                'rules'=>'trim|required'
            ),
            'password'=>array(
                'type'=>'password',
                'label'=>'Пароль',
                'rules'=>'trim'
            ),
            'port'=>array(
                'type'=>'text',
                'label'=>'Порт',
                'rules'=>'trim|required',
                'value'=>'3306'
            ),
            'database'=>array(
                'type'=>'text',
                'label'=>'Имя базы данных',
                'rules'=>'trim|required'
            ),
            'prefix'=>array(
                'type'=>'text',
                'label'=>'Префикс таблиц',
                'rules'=>'trim'
            )
        );
        
        $this->actions_view = 'forms/actions';
        
        if( !$this->submitted() )
        {
            $this->inputs['hostname']['value'] = 'localhost';
        }
    }
}