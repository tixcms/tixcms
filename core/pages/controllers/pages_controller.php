<?php

class Pages_Controller extends App\Controller 
{
    function __construct()
    {
        parent::__construct();

        // модель
        $this->load->model('categories/categories_m');
        $this->load->model('pages_m');
    }

    /**
     * Просмотр страницы
     */
    function action_view()
    {
        $segments = $this->uri->segment_array();        
        
        if( is_array($segments) )
        {
            $url = array_pop($segments);
            
            if( $segments )
            {
                $preurl = implode('/', $segments);
                $this->pages_m->by_pre_url($preurl);
            }
            
            $page = $this->pages_m->by_url($url)->get_one();
            
            // главная страница не должна быть доступна по урлу
            if( $page AND $page->is_main )
            {
                show_404();
            }
        }
        
        if( !$page AND !$segments )
        {
            $page = $this->pages_m->get_default();
        }
        
        if( !$page )
        {
            show_404();
        }

        // хлебные крошки
        $first_parent = false;
        if( $parents = $this->pages_m->get_parents($page, array('level >'=>0)) )
        {
            if( $parents )
            {
                foreach($parents as $parent)
                {
                    // главный родитель
                    if( $parent->level == 1 )
                    {
                        $first_parent = $parent;
                    }
                    
                    // хлебные крошки
                    $this->crumb($parent->title, $parent->full_url);
                }
            }
        }
        else
        {
            $parent = $page;
        }
        
        $this->crumb($page->title, $page->full_url);
        
        // сео
        $this->title($page->meta_title ? $page->meta_title : $page->title);

        $page->meta_description ? $this->description($page->meta_description) : '';
        $page->meta_keywords ? $this->keywords($page->meta_keywords) : '';
        
        if( $page->view )
        {            
            $view = $page->view;
        }
        else
        {
            $view = $page->is_leaf() ? 'page' : 'subpage';
        }

        $this->render($view, array(
            'first_parent'=>$first_parent,
            'page'=>$page,
            'parent'=>$parent
        ));
    }
}