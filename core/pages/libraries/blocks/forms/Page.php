<?php

namespace Pages\Blocks\Forms;

class Page extends \Block\Form
{
    function inputs()
    {
        $options = array();
        
        $this->load->model('pages/pages_m');
        
        $pages = $this->pages_m->order_by('lft ASC')->where('level > ', 0)->where('module', '')->where('url !=', 404)->get_all();
        
        foreach($pages as $page)
        {
            $options[$page->id] = $page->title;
        }
        
        return array(
            'page'=>array(
                'type'=>'select',
                'options'=>$options,
                'label'=>'Страница'
            )
        );
    }
}