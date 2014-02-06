<?php

namespace URL;

class Plugins extends \Tix\Plugin
{
    function base()
    {
        return \URL::base_url();
    }
    
    function anchor()
    {
        $href = $this->attribute('href');
        $text = $this->attribute('text');
        
        return \URL::anchor($href, $text);
    }
}