<?php

class Modules_m extends Tix\Model {

    public $table = 'modules';

    function categoriable()
    {
        $this->where('categoriable', 1);
        return $this;
    }
    
    function by_url($url)
    {
        $this->where('url', $url);
        return $this;
    }
    
    function by_name($name)
    {
        $this->where('name', $name);
        return $this;
    }
}