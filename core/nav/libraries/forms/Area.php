<?php

namespace Nav\Forms;

class Area extends \Admin\Form
{
    public $ajax = true;
    
    public $inputs = array(
        'name'=>array(
            'type'=>'text',
            'label'=>'Название',
            'rules'=>'trim|required|max_length[25]',
            'help'=>'Название области ссылок'
        ),
        'alias'=>array(
            'type'=>'text',
            'label'=>'Идентификатор',
            'rules'=>'trim|required|max_length[25]|alpha_dash|callback_area_unique',
            'help'=>'Идентификатор используется при вставки кода в шаблон'
        )
    );
    
    function before_save()
    {
        // если id не указан, то мы формируем его из названия
        $id = $this->get('alias') ? $this->get('alias') : $this->get('name');
        $this->set('alias', \Helpers\String::url_translit( $id ));
    }
    
    function before_update()
    {
        // меняем ссылки на новую область
        $this->nav_m
                    ->by_area_alias( $this->entity->alias )
                    ->set_area_alias( $this->get('alias') )
                    ->update();
    }
    
    /**
     * Проверяем идентификатор области на уникальность
     */
    function area_unique($area_alias)
    {
        if( !$area_alias )
        {
            $area_alias = \Helpers\String::url_translit($this->post('name'));
        }
        
        if( $this->nav_areas_m->by_alias($area_alias)->count() == 0 
            OR ($this->is_update() AND $this->entity->alias == $area_alias) )
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message(
                'area_unique', 
                'Такой идентификатор уже существует. Выберите другой'
            );
        
            return FALSE;
        }
    }
}