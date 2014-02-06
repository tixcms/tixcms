<?php

namespace Admin\Form\Input;

class Access extends \Form\Input
{
    public $label = 'Доступ';
    
    public function init()
    {                        
        parent::init();        
        
        $this->load->model('users/groups_m');        
        $groups = $this->groups_m->get_all();        
        $groups = \Helpers\CArray::map($groups, 'alias', 'name');
        
        $this->options = array('all'=>'Все') + $groups;
        
        $this->attrs['multiple'] = true;
        
        if( $this->form->is_insert() )
        {            
            if( $this->form->submitted() )
            {
                $this->value = set_value($this->field);
            }
            else
            {
                $this->value = array_keys($groups);
                $this->value[] = 'all';
            }
        }
        else
        {
            $access = $this->form->entity->{$this->field};
            
            if( $access == 'all' OR $access == '' )
            {
                $this->value = array_keys($groups);
                $this->value[] = 'all';
            }
            else
            {
                $groups = trim($access, '{}');
                $groups = explode('}{', $groups);
                
                $this->value = $groups;
            }
        }
    }
    
    function validate($str)
    {        
        if( in_array('all', $str) )
        {
            $this->form->set($this->field, 'all');
        }
        else
        {
            $this->form->set($this->field, '{'. implode('}{', $str) .'}');
        }
        
        return true;
    }
}