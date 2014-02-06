<?php

namespace Users\Forms\Backend;

/**
 * Форма групп пользователей
 */
class Group extends \Admin\Form
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
            'rules'=>'trim|required|callback_check_alias'
        )
    );
    
    function before_insert()
    {
        $this->set('alias', \Helpers\String::url_translit($this->get('alias')));
        
        // добавляем запись прав
        \CI::$APP->permissions_m->insert(array(
            'group_alias'=>$this->get('alias')
        ));
    }
    
    function init()
    {
        parent::init();
    }
    
    /**
     * Проверяем, что идентификатор группы еще не используется
     */
    function check_alias($string)
    {        
        if( $this->is_update() AND $this->entity->alias == $string )
        {
            return TRUE;
        }
        
        if( \CI::$APP->groups_m->by_alias($string)->get_one() === FALSE )
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('check_alias', 'Такой идентификатор уже используется');
            return FALSE;
        }
    }
}