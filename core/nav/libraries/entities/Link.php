<?php

namespace Nav\Entities;

class Link extends \Tix\Model\Entity
{
    function access()
    {
        return $this->access == 'all' 
            OR $this->access == '' 
            OR strstr($this->access, '{'. \CI::$APP->user->group_alias() .'}') !== false;
    }
}