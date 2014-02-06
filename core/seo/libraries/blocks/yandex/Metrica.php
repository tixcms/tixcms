<?php

namespace SEO\Blocks\Yandex;

class Metrica extends \Block
{
    protected $options = array(
        'id'=>'',
        'webvisor'=>FALSE,
        'clickmap'=>FALSE,
        'trackLinks'=>FALSE,
        'accurateTrackBounce'=>FALSE,
        'ut'=>FALSE,
        'trackHash'=>FALSE
    );
    
    function data()
    {
        $this->set_yandex_options();
    }
    
    function set_yandex_options()
    {
        $options['id'] = $this->options['id'];
        
        $items = array(
            'webvisor', 
            'clickmap', 
            'trackLinks', 
            'accurateTrackBounce', 
            'trackHash'
        );
        
        foreach($items as $item)
        {
            if( $this->options[$item] )
            {
                $options[$item] = TRUE;
            }
        }
        
        if( $this->options['ut'] )
        {
            $options['ut'] = "noindex";
        }
        
        $this->metrica_options = json_encode($options);
    }
}