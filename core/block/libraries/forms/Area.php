<?php

namespace Block\Forms;

class Area extends \Admin\Form
{    
    public $inputs = array(
        'name'=>array(
            'type'=>'text',
            'label'=>'Название',
            'rules'=>'trim|required'
        ),
        'alias'=>array(
            'type'=>'text',
            'label'=>'Идентификатор',
            'rules'=>'trim|max_length[25]'
        )
    );
    
    function before_save()
    {
        if( !$this->get('alias') )
        {
            $this->set('alias', $this->get('name'));
        }
        
        $this->set('alias', \Helpers\String::url_translit($this->get('alias')));
    }
    
    function before_update()
    {        
        // меняем ссылки на новую область
        $this->block_m->by_area_alias($this->entity->alias)->set_area_alias($this->get('alias'))->update();
    }
}