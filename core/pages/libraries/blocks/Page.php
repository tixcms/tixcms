<?php

namespace Pages\Blocks;

class Page extends \Block
{
    function data()
    {        
        $page = $this->pages_m->by_id($this->options['page'])->get_one();
        
        return array(
            'pageEntity'=>$page
        );
    }
}