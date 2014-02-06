<?php

namespace Categories\Form;

class Input extends \Form\Input
{
    public $default_option;
    public $options;
    public $filter = array();
    public $module = false;
    public $indent = '&nbsp;&nbsp;&nbsp;';
    public $start_level = 1;
    
    function init()
    {
        parent::init();
        
        $this->view = $this->form->inputs_folder .'/select';
        $this->options = $this->options('', $this->default_option);
        
        if( $this->form->is_update() )
        {
            $this->value = $this->form->entity->{$this->field};
        }
    }
    
    function options($where = array(), $default_option = FALSE)
    {
        $this->load->model('categories/categories_m');
        
        if( $this->module )
        {
            $this->categories_m->by_module($this->module);
            $this->categories_m->where('level !=', 0);
        }
        
        if( $this->filter )
        {
            $this->categories_m->where($this->filter);
        }
        
        $categories = $this->categories_m->order_by('lft', 'ASC')->get_all();
        
        $options = $default_option ? array($default_option) : array();
        if( $categories )
        {
            foreach($categories as $category)
            {
                $options[$category->id] = str_repeat($this->indent, $category->level - $this->start_level) . $category->title;
            }
        }
                
        return $options;
    }
    
    function validate($str)
    {
        return true;
    }
}