<?php

namespace Block;

abstract class Form extends \Admin\Form
{    
    public $block;
    public $ajax = true;
    
    function before_save()
    {
        $data = array();
        if( $this->inputs )
        {
            foreach($this->inputs as $name=>$input)
            {
                if( isset($input->serializable) )
                {
                    $data[$input->field] = $this->get($input->field);
                    $this->un_set($input->field);
                }
            }
        }
        
        $this->set('data', serialize($data));
    }
    
    function inputs()
    {
        return array();
    }
    
    function before_insert()
    {
        $this->set('block_module', $this->block['module']);
        $this->set('block_class', $this->block['class']);
        $this->set('block_alias', $this->block['alias']);
    }
    
    function init()
    {
        parent::init();
        
        // добавляем сериализованные данные, если редактируем
        if( $this->entity )
        {            
            $data = unserialize($this->entity->data);
            
            foreach($data as $name=>$value)
            {
                $this->entity->$name = $value;
            }
        }

        $this->load->model('block/block_areas_m');
        
        $this->inputs = array(
            'area_alias'=>array(
                'type'=>'select',
                'label'=>'Область',
                'rules'=>'trim|required',
                'options'=>\Block_areas_m::options(),
                'help'=>'Область, в которой будет показываться блок'
            ),
            'title'=>array(
                'type'=>'text',
                'label'=>'Заголовок',
                'rules'=>'trim|required',
                'help'=>'Название блока'
            ),
            'show_title'=>new \Form\Input\Checkbox(array(
                'label'=>'Показывать заголовок',
                'help'=>'Если включено, то при выводе будет отображаться заголовок блока'
            )),
            'active'=>new \Form\Input\Checkbox(array(
                'label'=>'Включен'
            )),
            'access'=>$this->load->library('Admin\Form\Input\Access')
        );
        
        $block_inputs = $this->inputs();
        
        if( $block_inputs )
        {
            foreach($block_inputs as $name=>$input)
            {
                $this->inputs[$name] = (object)$input;
                $this->inputs[$name]->serializable = true;                
                $this->inputs[$name]->xss = isset($this->inputs[$name]->xss) ? $this->inputs[$name]->xss : true;                
            }
        }
    }
}