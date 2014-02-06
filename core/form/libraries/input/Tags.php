<?php

namespace Form\Input;

class Tags extends \Form\Input
{
    public $model;
    public $tags = array();
    
    function init()
    {
        parent::init();
        
        if( $this->form->is_update() AND $this->form->entity->{$this->field} )
        {
            $this->value = str_replace(',', ', ', $this->form->entity->{$this->field});
        }
    }
    
    function validate($str)
    {
        if( $str )
        {
            $this->tags = $this->string_to_tags($str);
            
            $this->form->set($this->field, implode(',', $this->tags));
        }
        
        return true;
    }
    
    function after_save()
    {
        if( $this->tags )
        {
            $this->load->model('tags/tags_m');
            
            $new_tags = array();
            $foreign_id = $this->form->is_update() ? $this->form->entity->id : $this->form->insert_id;
            
            if( $this->form->is_update() )
            {
                $old_tags = $this->tags_m->by_item_id($foreign_id)->by_module(\CI::$APP->module->url)->get_all();
                $old_tags = $old_tags ? \Helpers\CArray::map($old_tags, 'id', 'tag') : array();
                
                $this->delete_removed_tags($old_tags, $this->tags);
                
                $new_tags = $old_tags ? array_diff($this->tags, $old_tags) : $this->tags;
            }
            else
            {
                $new_tags = $this->tags;
            }
            
            foreach($new_tags as $tag)
            {
                $this->tags_m->set_item_id($foreign_id)->set_tag($tag)->set_module(\CI::$APP->module->url)->insert();
            }
        }
    }
    
    function string_to_tags($string)
    {
        $tags = array();

        if($string)
        {
            $temp = explode(',', $string);

            foreach($temp as $item)
            {
                if(trim($item))
                {
                    $tags[] = trim($item, ', ');
                }
            }
        }

        return $tags;
    }
    
    /**
     * Удаление из таблицы убранных тегов
     * 
     * @param array Массив тегов до обновления
     * @param array Массив тегов после добавления
     */
    function delete_removed_tags($old_tags, $new_tags)
    {        
        if( $deleted_tags = array_diff($old_tags, $new_tags) )
        {
            $this->tags_m->where_in('id', array_keys($deleted_tags))->delete();
        }
    }
}