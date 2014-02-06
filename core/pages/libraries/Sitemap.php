<?php

namespace Pages;

class Sitemap extends \Sitemap
{
    function get_data()
    {
        $this->load->model('categories/categories_m');
        $this->load->model('pages/pages_m');
        
        $pages = $this->pages_m->by_is_active(1)->where('url !=', '404')->where('level >', 0)->get_all();
        
        $data = array();
        
        if( $pages )
        {
            foreach($pages as $page)
            {
                $data[] = array(
                    'loc'=>$this->di->url->site_url($page->full_url),
                    'lastmod'=>date('c', $page->updated_on),
                    'changefreq'=>'daily',
                    'priority'=>$page->is_main ? 1 : pow(0.8, $page->level)
                );
            }
        }
        
        return $data;
    }
}