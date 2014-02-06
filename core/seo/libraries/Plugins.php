<?php

namespace Seo;

class Plugins extends \Tix\Plugin
{
    function title()
    {
        return \CI::$APP->di->seo->site_title();
    }
    
    function description()
    {
        return \CI::$APP->di->seo->description();
    }
    
    function keywords()
    {
        return \CI::$APP->di->seo->keywords();
    }
}