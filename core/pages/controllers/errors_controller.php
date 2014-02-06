<?php

class Errors_Controller extends App\Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->template->layout = array();
        $this->template->add_layout($this->config->item('layout'));
        $this->template->parse = true;
        
        CI::$APP->controller = 'errors';
        CI::$APP->action = 'error';
    }
    
    function action_error($data = '')
    {
        if( empty($data) )
        {
            $this->error404();
        }
        else
        {
            set_status_header($data['status_code']);
            
            $template = $data['template'] ? $data['template'] : 'pages::errors/general';
            
            $this->render($template, $data);
        
            exit;
        }
    }
    
    /**
     * Вывод 404 страницы
     */
    function action_404()
    {
        header("HTTP/1.0 404 Not Found");

        /* когда грузит картинку/флеш, а ее нет, попадает сюда */
        $uri  = strtolower(pathinfo($this->uri->uri_string(), PATHINFO_EXTENSION));
        $type = substr($_SERVER['HTTP_ACCEPT'], 0, 5);
        
        if($type == 'image' OR in_array($uri, array('swf', 'flv', 'css', 'js', 'ico')))
        {
            die();
        }
        /* --------------------------------------------------- */

        $this->load->model('categories/categories_m');
        $this->load->model('pages/pages_m');
        
        $page = $this->pages_m->by_url(404)->get_one();

        if( $page )
        {
            $this->render('pages::page', array(
                'page'=>$page
            ));
        }
        else
        {
            $this->render('pages::errors/404');
        }
    }
    
    function general($params)
    {
        $view = isset($params['view']) ? $params['template'] : 'errors/general';
        
        $this->render($view, $params);
    }
    
    /**
     * Errors page has all access
     */
    function page_has_access()
    {
        return true;
    }
}