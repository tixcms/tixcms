<?php

include(APPPATH . '../core/purifier/libraries/HTMLPurifier.auto.php');

class Purifier extends HTMLPurifier 
{
    function __construct($type = 'default')
    {
        $config = HTMLPurifier_Config::createDefault();        
        
        if( is_string($type) )
        {
            \CI::$APP->load->config('purifier/config');
            $data = \CI::$APP->config->item('purifier');
            $data = $data[$type];
        }
        else
        {
            $data = $type;
        }
        
        if( array_key_exists('AutoFormat.AutoParagraph', $data) AND $data['AutoFormat.AutoParagraph'] )
        {
            //$config->set('Output.Newline', "\n");
        }
        
        foreach($data as $key=>$value)
        {
            $config->set($key, $value);
        }
        
        $config->set('Cache.SerializerPath', 'cache');
        
        return parent::__construct($config);
    }
    
    function purify($html, $config = null)
    {
        $html = str_replace(
            array('<p><br></p>', '<p></p>', '<div><br></div>'),
            array('<p>&nbsp;</p>', '<p>&nbsp;</p>', '<p>&nbsp;</p>'),
            $html
        );
        $html = preg_replace("/<br[^>]*><br[^>]*>/", "\n\n", $html);
        $html = preg_replace('/\<p\>\<[a-zA-Z]+\>\<br\>\<\/[a-zA-Z]+\>\<\/p\>/', '<p>&nbsp;</p>', $html);
        $html = preg_replace('/\<div\>\<[a-zA-Z]+\>\<br\>\<\/[a-zA-Z]+\>\<\/div\>/', '<p>&nbsp;</p>', $html);
        $html = parent::purify($html, $config);

        return $html;
    }
}