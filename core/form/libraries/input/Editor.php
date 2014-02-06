<?php

namespace Form\Input;

class Editor extends \Form\Input
{
    public $view = 'editor';
    public $elements;
    public $elements_count;
    
    public function init()
    {
        parent::init();
        
        $this->load->model('form/editor_m');
        
        $this->generator = new \Form\Input\Editor\Generator;
        
        if( $this->form->is_insert() )
        {
            if( $this->form->submitted() )
            {
                $this->elements = $this->form->post($this->field);                
                $this->elements_count = $this->elements ? count($this->elements) + 1 : 1;
            }
            else
            {
                $this->elements = array();
                $this->elements_count = 1;
            }
        }
        else
        {
            $id = $this->get_id();
            
            $editor = $this->editor_m->by_id($id)->get_one();                     
            $this->elements = ($editor AND $editor->elements != 'false') 
                ? (array)json_decode($editor->elements) : false;            
            $this->elements_count = $this->elements ? count($this->elements) + 1 : 0;
        }
        
        $this->form->di->assets->js('jquery::ui/sortable.min.js');
    }
    
    public function validate()
    {
        $elements = $this->form->post($this->field);
        
        $content = '';        
        if( $elements )
        {
            foreach($elements as $element)
            {
                $content .= $this->elementToHTML($element);
            }
        }
        
        $this->form->set($this->field, $content);
        
        return true;
    }
    
    public function after_save()
    {
        $id = $this->get_id();
        
        $elements = array();        
        foreach($this->form->post($this->field) as $element)
        {
            $elements[] = $this->elementNormalize($element);
        }
        
        if( $this->form->is_insert() OR $this->editor_m->by_id($id)->count() == 0 )
        {
            $this->editor_m->insert(array(
                'id'=>$id,
                'elements'=>json_encode($elements)
            ));
        }
        else
        {
            $this->editor_m
                ->by_id($id)
                ->set_elements(json_encode($elements))
                ->update();
        }
    }
    
    public function get_id()
    {
        return $this->form->module->url .'-'. $this->form->controller .'-'
            . ( $this->form->is_insert() 
                ? $this->form->insert_id 
                : $this->form->entity->{$this->form->model->primary_key}
        );
    }
    
    public function elementNormalize($element)
    {
        switch($element['type'])
        {
            default:
                return $element;
        }
    }
    
    public function elementToHTML($element)
    {
        switch($element['type'])
        {
            case 'paragraph':                
                $content = str_replace(array('<p>', '</p>'), array('', ''), $element['content']);
            
                return '<p>'. nl2br($content) .'</p>';
                break;
                
            case 'header':
                $header = $element['header'];
                return '<h'. $header .'>'. $element['content'] .'</h'. $header .'>';
                break;
                
            case 'code':            
                return '<pre class="brush: php">'. strip_tags( $element['content'] ) .'</pre>';
                break;
                
            case 'image':
                return '<p style="text-align: '. $element['align'] .';"><img src="'. $element['src'] .'"></p>';
                break;
                
            case 'blockquote':
                return '<blockquote>'. $element['content'] .'</blockquote>';
                break;
                
            default:
                return '';
        }
    }
}