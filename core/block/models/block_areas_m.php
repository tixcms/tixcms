<?php

class Block_areas_m extends Tix\Model {
    
    public $table = 'blocks_areas';
    
    static function options()
    {
        $areas = CI::$APP->block_areas_m->get_all();
        
        $options = array();
        if( $areas )
        {
            foreach($areas as $area)
            {
                $options[$area->alias] = $area->name;
            }
        }
        
        return $options;
    }
}