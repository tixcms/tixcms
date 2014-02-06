<?php

namespace Admin\Blocks\Nav;

class Sidebar extends \Admin\Blocks\Nav\NavList
{
    function data()
    {
        $groups = \Modules\Helper::get_groups();
        $modules_by_groups = \Modules\Helper::get_modules_by_groups();
        
        $items = array();
        if( $groups )
        {
            if( isset($modules_by_groups['no_group']) )
            {
                foreach($modules_by_groups['no_group'] as $item )
                {
                    $items[] = array(
                        'label'=>$item->name() .
                            (\Notices::has($item->url) 
                                ? '<span class="badge badge-important pull-right">'. \Notices::get_count($item->url()) .'</span>' 
                                : '' 
                            ),
                        'url'=>'admin/'. $item->url(),
                        'active'=>\CI::$APP->module == $item->url()
                    );
                }
                
                unset($modules_by_groups['no_group']);
            }            
            
            foreach($groups as $group)
            {
                if( isset($modules_by_groups[$group->alias]) )
                {
                    $items[] = $group->name;
                    
                    foreach($modules_by_groups[$group->alias] as $item )
                    {
                        $items[] = array(
                            'label'=>$item->name() .
                                (\Notices::has($item->url()) 
                                    ? '<span class="badge badge-important pull-right">'. \Notices::get_count($item->url()) .'</span>' 
                                    : '' 
                                ),
                            'url'=>'admin/'. $item->url(),
                            'active'=>\CI::$APP->module == $item->url()
                        );
                    }
                }
            }
        }
        
        return array(
            'items'=>$items
        );
    }
}