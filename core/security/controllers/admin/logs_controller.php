<?php

class Logs_Controller extends Security\Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('logs_m');
        
        $this->template->remove_layout();
    }
    
    function action_index($page = 1)
    {
        $table = new \Security\Tables\Logs(array(
            'model'=>$this->logs_m
        ));
        
        if( $this->is_ajax() )
        {
            echo $table->render('json');

            return;
        }
        
        $this->render(array(
            'table'=>$table
        ));
    }
}