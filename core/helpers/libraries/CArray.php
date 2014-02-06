<?php

namespace Helpers;

class CArray {
    
    static function map($haystack, $key, $value = false)
    {
        $foo = array();
        foreach($haystack as $item)
        {
            $foo[$item->$key] = $value ? $item->$value : $item;
        }
        
        return $foo;
    }
}