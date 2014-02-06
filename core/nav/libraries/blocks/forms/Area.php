<?php

namespace Nav\Blocks\Forms;

class Area extends \Block\Form
{    
    function inputs()
    {
        \CI::$APP->load->model('nav/nav_areas_m');
        
        $areas = \CI::$APP->nav_areas_m->get_all();
        
        $options = array();
        if( $areas )
        {
            foreach($areas as $area)
            {
                $options[$area->alias] = $area->name;
            }
        }
        
        return array(
            'area'=>array(
                'type'=>'select',
                'options'=>$options,
                'rules'=>'trim|required',
                'label'=>'Область ссылок'
            ),
        );
    }
}