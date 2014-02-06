<?php

namespace Nav\Blocks;

class Area extends \Block
{    
    public $view = 'nav::blocks/area';
    public static $links_by_areas = null;
    
    function data()
    {
        if( self::$links_by_areas === null )
        {
            // загружаем модель
            $this->load->model('nav/nav_m');
            
            // выбираем ссылки области
            $links = $this->nav_m->where('status', 1)->order_by('order', 'ASC')->get_all();

            if( $links )
            {
                foreach($links as $link)
                {
                    if( $link->access() )
                    {
                        self::$links_by_areas[$link->area_alias][$link->parent_id][] = $link;
                    }
                }
            }
            else
            {
                self::$links_by_areas = false;
            }
        }
        
        $links = isset(self::$links_by_areas[$this->options['area']]) 
            ? self::$links_by_areas[$this->options['area']] : false;
        
        return array(
            'links'=>$links,
            'parent_id'=>0,
            // uri текущей страницы. Нужен для определения активной ссылки
            'current_uri'=>$this->uri->uri_string()
        );
    }
}