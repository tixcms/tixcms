<?php

namespace Categories;

class Entity extends \Tix\Model\Entity 
{
    function is_leaf()
    {
        return $this->rgt - $this->lft == 1;
    }
    
    function img()
    {
        return $this->icon;
    }
    
    function meta_title()
    {
        return $this->meta_title ? $this->meta_title : $this->title;
    }
}