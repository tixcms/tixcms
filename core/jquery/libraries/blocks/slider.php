<?php

namespace jQuery\Blocks;

class Slider extends \Block
{
    static public $id = 1;
    public $options = array(
        'theme'=>'dark',
        'items'=>array(),
        'options'=>array()
    );
    public $nivo_options = array(
        'effect'=>'random',
        'slices'=> 15, // For slice animations
        'boxCols'=> 8, // For box animations
        'boxRows'=> 4, // For box animations
        'animSpeed'=> 500, // Slide transition speed
        'pauseTime'=> 3000, // How long each slide will show
        'startSlide'=> 0, // Set starting Slide (0 index)
        'directionNav'=> true, // Next & Prev navigation
        'controlNav'=> true, // 1,2,3... navigation
        'controlNavThumbs'=> false, // Use thumbnails for Control Nav
        'pauseOnHover'=> true, // Stop animation while hovering
        'manualAdvance'=> false, // Force manual transitions
        'prevText'=> 'Prev', // Prev directionNav text
        'nextText'=> 'Next', // Next directionNav text
        'randomStart'=> false, // Start on a random slide
        //'beforeChange'=> 'function(){}', // Triggers before a slide transition
        //'afterChange'=> 'function(){}', // Triggers after a slide transition
        //'slideshowEnd'=> 'function(){}', // Triggers after all slides have been shown
        //'lastSlide'=> 'function(){}', // Triggers when last slide is shown
        //'afterLoad'=> 'function(){}' // Triggers when slider has loaded
    );
    
    function data()
    {
        \CI::$APP->di->assets->js('jquery::jquery.nivo.slider.pack.js');
        \CI::$APP->di->assets->css('jquery::nivo/nivo-slider.css');
        \CI::$APP->di->assets->css('jquery::nivo/themes/'. $this->options['theme'] .'/'. $this->options['theme'] .'.css');
        
        $this->options['options'] = array_merge($this->nivo_options, $this->options['options']);
        
        $this->id = self::$id;
        self::$id++;
    }
}