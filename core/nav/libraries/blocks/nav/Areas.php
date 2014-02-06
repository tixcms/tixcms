<?php

namespace Nav\Blocks\Nav;

class Areas extends \Admin\Blocks\Nav\Tabs
{    
    public $options = array(
        'dynamic'=>true,
        'link'=>true
    );
    
    public $view = 'blocks/nav/areas';
    
    function data()
    {
        parent::data();
        
        \CI::$APP->load->model('nav/nav_areas_m');        
        $areas = \CI::$APP->nav_areas_m->get_all();

        $items = array();
        if( $areas )
        {
            $i=0;
            foreach($areas as $area)
            {                
                $this->options['items'][] = array(
                    'url'=>$area->alias,
                    'label'=>$area->name,
                    'active'=>$i==0,
                    'content'=>$this->template->view('areas/_item', array(
                        'area'=>$area,
                        'i'=>$i
                    ))
                );
                $i++;
            }
        }
        
        return array(
            'items'=>$items
        );
    }
}