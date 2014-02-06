<?php

namespace Categories;

class Table extends \Admin\Table
{
    public $headings = array(
        'Название',
        'Элементов',
        'Показывать',
        'Действия'
    );
    public $item_view = 'categories::_item';
    public $no_items = 'Нет категорий';
    public $default_sort = 'lft ASC';
    
    function init()
    {
        parent::init();
    }
}