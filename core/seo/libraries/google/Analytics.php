<?php

namespace SEO\Google;

class Analytics 
{
    private $tracking_ID;
    
    static function create($options = array())
    {
        return new self($options);
    }
    
    function __construct($options = array())
    {
        foreach($options as $key => $value)
        {
            $this->$key =$value;
        }
    }
    
    function render()
    {
        return \CI::$APP->template->view('seo::google/analytics', array(
            'tracking_ID'=>$this->tracking_ID
        ));
    }
}