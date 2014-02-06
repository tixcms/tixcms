<?php

namespace Settings;

abstract class Form extends \Admin\Form
{    
    public $settings;
    public $view = 'settings::_form';
    public $entity;
    public $ajax = true;
    public $module = '';
    public $csrf_protection = false;
    public $personal_settings = array();
    
    abstract function inputs();
    
    function init()
    {
        parent::init();

        $this->inputs = $this->inputs();
        $this->actions = $this->actions();
        
        $this->entity = new \stdClass;

        $this->entity = new \stdClass;

        foreach($this->inputs as $name=>$input)
        {
            if( $this->is_input_personal($input) )
            {
                if( isset(\CI::$APP->user) )
                {
                    $this->entity->$name = isset($this->user->settings[$name]) 
                        ? $this->user->settings[$name] : (
                            is_object($input) ? $input->default : $input['default']
                        );
                }
            }
            else
            {
                $this->entity->$name = isset(\CI::$APP->settings->$name) ? \CI::$APP->settings->$name : false;
            }
            
            // use wysiwyg
            if( is_array($input) AND isset($input['attrs']['class']) AND strpos($input['attrs']['class'], 'wysiwyg') !== false )
            {
                $data['admin'][$this->module][$this->controller] = '*';

                $this->config->set_item('wysiwyg_active', $data);
            }
        }
    }
    
    function actions()
    {
        $this->actions['submit']['value'] = 'Сохранить';
        
        $actions = array(
            'back_url'=>$this->actions['back_url'],
            'submit'=>$this->actions['submit']
        );
        
        if( $this->module == 'settings' )
        {
            unset($actions['back_url']);
        }
        
        return $actions;
    }
    
    /**
     *
     */
    protected function set_after_input()
    {
        // проставляем value
        foreach($this->inputs as $name=>$input)
        {
            if( isset($this->inputs[$name]->code) AND $this->inputs[$name]->code )
            {
                $this->inputs[$name]->after_input = '<small class="muted" title="Код для вставки">{{settings:item id="'. $name .'"}}</small>';
            }
            else
            {
                $this->inputs[$name]->after_input = false;
            }
        }
    }
    
    function before_render()
    {
        $this->set_after_input();
        
        parent::before_render();
    }
    
    function need_to_turn_ajax_off()
    {
        return false;
    }
        
    function save()
    {
        foreach($this->inputs as $input)
        {
            if( !$this->is_input_personal($input) )
            {                
                \CI::$APP->settings_m
                    ->by_id( $input->field )
                    ->set_value( $this->get($input->field) )
                    ->update();
            }
            else
            {
                $this->personal_settings[$input->field] = $this->get($input->field);
            }
        }

        if( $this->personal_settings )
        {
            $settings = array_merge($this->user->settings, $this->personal_settings);
            
            $this->users_m->by_id($this->user->id)->set_settings(serialize($settings))->update();
        }
    }

    function is_input_personal($input)
    {
        return isset($input->personal) OR (is_array($input) AND isset($input['personal']));
    }
}