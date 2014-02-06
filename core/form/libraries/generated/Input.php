<?php

namespace Form\Generated;

class Input extends \Form\Input
{
    public $view = 'form::_input';
    
    function init()
    {
        parent::init();
        
        if( $this->form->is_update() )
        {
            $this->inputs = json_decode($this->form->entity->{$this->field});
        }
        else
        {
            $this->inputs = array();
        }
    }
    
    function validate()
    {
        $inputs = $this->form->post('inputs');
        
        if( $inputs )
        {
            $result = array();
            foreach($inputs as $input)
            {                
                $result[$input['alias']] = array(
                    'type'=>$input['type'],
                    'label'=>$input['label'],
                    'required'=>isset($input['required']) ? true : false,
                    'help'=>$input['help'],
                    'valid_email'=>isset($input['valid_email']) ? true : false
                );
            }
            
            $this->form->set($this->field, json_encode($result));
            
            return true;
        }
        else
        {
            $this->error = 'Не добавлено ни одного поля';
            
            return false;
        }
    }
    
    static function render_input($alias, $input, $i)
    {
        return \CI::$APP->template->view('inputs/_shared', array(
            'alias'=>$alias,
            'input'=>$input,
            'i'=>$i
        ));
    }
}