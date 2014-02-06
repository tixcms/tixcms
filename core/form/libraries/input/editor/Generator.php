<?php

namespace Form\Input\Editor;

class Generator
{
    private $element_type;
    private $element_index;
    private $editor_name;
    private $data;
    private $di;
    
    public function set_element($element_type, $element_index, $editor_name, $data = array())
    {
        $this->element_type = $element_type;
        $this->element_index = $element_index;
        $this->editor_name = $editor_name;
        $this->data = $data;
        
        return $this;
    }
    
    public function run()
    {
        return $this->get_di()->template->view('form::editor/elements/'. $this->element_type, array(
            'element_index'=>$this->element_index,
            'editor_name'=>$this->editor_name,
            'data'=>$this->data
        ));
    }
    
    public function get_di()
    {
        if( !$this->di )
        {
            $this->di = \CI::$APP->di;
        }
        
        return $this->di;
    }
    
    public function set_di($di)
    {
        $this->di = $di;
    }
    
    public function __get($name)
    {
        return $this->get_di()->$name;
    }
}