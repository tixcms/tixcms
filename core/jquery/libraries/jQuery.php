<?php

class jQuery 
{
    function render($type = '')
    {
        if( $type == 'cdn' )
        {
            return HTML\Tag::js('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
        }
        else
        {
            return CI::$APP->di->assets->render_js('jquery::jquery.min.js');
        }
    }
    
    function plugin($plugin, $render = FALSE)
    {
        return jQuery\Plugins::init()->plugin($plugin, $render);
    }
}