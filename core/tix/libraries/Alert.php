<?php

namespace Tix;

use CI;

class Alert {
    
    private $type;
    private $message = array();
    private $has_message = FALSE;
    private $folder = 'app::alerts/';
    private $closeable = array();
    
    function __construct($params = array())
    {
        if( $params )
        {
            foreach($params as $key=>$value)
            {
                $this->$key = $value;
            }
        }
        
        $alert = CI::$APP->session->flashdata('alert');

        $this->set($alert['type'], $alert['message'][$alert['name']], $alert['name'], $alert['closeable']);
    }
    
    function set($type, $message, $name = false, $closeable = true)
    {
        $name = $name ? $name : 'default';
        
        $this->type[$name] = $type;
        $this->message[$name] = $message;
        $this->closeable[$name] = $closeable;
    }
    
    function set_flash($type, $message, $name = 'default', $closeable = true)
    {
        $name = $name ? $name : 'default';
        
        CI::$APP->session->set_flashdata('alert', array(
            'name'=>$name,
            'type'=>$type,
            'message'=>array(
                $name=>$message
            ),
            'closeable'=>$closeable
        ));
    }
    
    function set_folder($folder)
    {
        $this->folder = $folder;
    }
    
    function render($name = 'default')
    {
        $name = $name ? $name : 'default';

        if( isset($this->message[$name]) )
        {
            $view = $this->folder . $this->type[$name];
            
            return CI::$APP->template->view($view, array(
                'message'=>$this->message[$name],
                'closeable'=>$this->closeable[$name]
            ));
        }        
    }
    
    function has_message($name = 'default')
    {
        $name = $name ? $name : 'default';
        
        return isset($this->message[$name]);
    }
    
    function message($type, $message, $closeable = true)
    {
        $name = \Helpers\String::random();
        
        $this->set($type, $message, $name, $closeable);
        return $this->render($name);
    }
}