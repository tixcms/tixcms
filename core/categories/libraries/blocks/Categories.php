<?php

namespace Categories\Blocks;

abstract class Categories extends \Block
{
    public $options = array(
        'module'=>'',
        'is_active'=>1,
        'start_level'=>1
    );
    public $view = 'categories::blocks/categories';
    
    function data()
    {
        $this->load->model('categories/categories_m');

        $categories = $this->categories_m
            ->by_module($this->options['module'])
            ->where('level >=', $this->options['start_level'])
            ->where('is_active', $this->options['is_active'])
            ->order_by('lft', 'ASC')
            ->get_all();

        return array(
            'categories'=>$categories
        );
    }
}