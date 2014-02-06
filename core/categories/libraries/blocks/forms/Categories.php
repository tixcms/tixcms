<?php

namespace Categories\Blocks\Forms;

abstract class Categories extends \Block\Form
{    
    function inputs()
    {
        return array();
    }
    
    function before_save()
    {
        $data = unserialize($this->get('data'));
        
        $data['module'] = $this->block['module'];
        
        $this->set('data', serialize($data));
    }
}