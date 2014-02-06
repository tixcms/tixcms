<?php

namespace Form\Input;

/**
 * Добавляет поля заголовок, описание и ключевые слова
 * 
 * В массиве fields указываются поля соответствующие элементов в целевой таблице
 * В массиве placeholders значения полей
 * Также можно указать placeholders_fields названия полей из таблицы, либо closure функцию
 * 
 * function($entity){
 *     return $entity->title;
 * }
 */
class Meta extends \Form\Input
{
    public $view = 'meta';
    public $save = false;
    
    public $labels = array(
        'title'=>'Заголовок страницы',
        'description'=>'Описание страницы',
        'keywords'=>'Ключевые слова'
    );
    
    public $fields = array(
        'title'=>'meta_title',
        'description'=>'meta_description',
        'keywords'=>'meta_keywords'
    );
    
    public $placeholders = array(
        'title'=>'',
        'description'=>'',
        'keywords'=>''
    );
    
    public $placeholders_fields = array(
        'title'=>false,
        'description'=>false,
        'keywords'=>false
    );
    
    public $slide = NULL;
    
    function init()
    {
        parent::init();
        
        if( $this->form->is_insert() AND $this->slide === NULL )
        {
            $this->slide = !!$this->input->post('meta-slide');
        }
        
        foreach($this->fields as $field=>$value )
        {
            if( $this->form->is_update() )
            {
                $$field = $this->form->entity->{$this->fields[$field]};
                
                if( !$$field AND $this->placeholders_fields[$field] )
                {
                    if( is_callable($this->placeholders_fields[$field]) )
                    {
                        $this->placeholders[$field] = $this->placeholders_fields[$field]($this->form->entity);
                    }
                    else
                    {
                        $this->placeholders[$field] = $this->form->entity->{$this->placeholders_fields[$field]};
                    }
                }
            }
            else
            {
                $$field = set_value($this->fields[$field]);
            }
        }
        
        $this->values = array(
            'title'=>$title,
            'description'=>$description,
            'keywords'=>$keywords
        );
    }
    
    function validate()
    {
        $this->form->set($this->fields['title'], $this->form->post($this->fields['title']));
        $this->form->set($this->fields['description'], $this->form->post($this->fields['description']));
        $this->form->set($this->fields['keywords'], $this->form->post($this->fields['keywords']));
        
        return TRUE;
    }
}