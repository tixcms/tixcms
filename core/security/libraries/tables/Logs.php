<?php

namespace Security\Tables;

class Logs extends \Admin\Table
{
    public $headings = array(
        'type'=>array(
            'label'=>'Вход/Выход',
            'sortable'=>true
        ),
        'user_id'=>array(
            'label'=>'Пользователь',
            'sortable'=>true
        ),
        'created_on'=>array(
            'label'=>'Дата',
            'sortable'=>true
        ),
        'ip'=>array(
            'label'=>'IP',
            'sortable'=>true
        ),
        'user_agent'=>array(
            'label'=>'Браузер',
            'sortable'=>true
        ),
        'backend'=>array(
            'label'=>'Админка',
            'sortable'=>true
        )
    );
    public $ajax = true;
    public $item_view = 'logs/_item';
    public $no_items = 'Нет данных';
    public $per_page = 15;
    public $default_sort = 'created_on DESC';
    
    function init()
    {
        parent::init();
        
        $this->filters = array(
            'type'=>array(
                'options'=>array(
                    'all'=>'Все типы',
                    \Logs_m::TYPE_LOGIN=>'Вход',
                    \Logs_m::TYPE_LOGOUT=>'Выход',
                    \Logs_m::TYPE_FAIL_LOGIN=>'Неудачный вход'
                )
            ),
            'backend'=>array(
                'options'=>array(
                    'all'=>'Все части',
                    1=>'Админка',
                    'off'=>'Публичная часть'
                )
            )
        );
    }
    
    function get_items()
    {
        $this->model->with('users');
        
        return parent::get_items();
    }
}