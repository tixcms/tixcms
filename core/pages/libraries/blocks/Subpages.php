<?php

namespace Pages\Blocks;

class Subpages extends \Block
{
    public $options = array(
        'page'=>false
    );
    
    function data()
    {
        $subpages = $this->pages_m->order_by('lft', 'ASC')->get_childs($this->options['page'], true);
        
        return array(
            'subpages'=>$subpages
        );
    }
}