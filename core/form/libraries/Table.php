<?php

namespace Form;

class Table extends \Admin\Table
{
    public $no_items = 'Нет форм';
    public $item_view = '_item';
    public $headings = array(
        'Название',
        'Код для вставки',
        'Действия'
    );
    public $search = null;
}