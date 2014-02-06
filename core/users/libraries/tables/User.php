<?php

namespace Users\Tables;

class User extends \Admin\Table
{
    public $item_view = '_item';
    public $no_items = 'Нет пользователей';
    public $default_sort = 'register_date DESC';
    public $headings = array(
        '<input type="checkbox" name="" class="check-all" />',
        'login'=>array(
            'label'=>'Логин',
            'sortable'=>true,
            'searchable'=>true
        ),
        'email'=>array(
            'label'=>'Почта',
            'sortable'=>true,
            'searchable'=>true
        ),
        'group_alias'=>array(
            'label'=>'Группа',
            'sortable'=>true
        ),
        'register_date'=>array(
            'label'=>'Дата регистрации',
            'sortable'=>true
        ),
        'is_active'=>array(
            'label'=>'Статус',
            'sortable'=>true
        ),
        'Действия'
    );
    public $search = true;    
    public $ajax = true;
    public $mass_actions_view = '_mass_actions';
    
    function init()
    {
        parent::init();

        if( !$this->settings->users_must_use_login )
        {
            unset($this->headings['login']);
        }

        $this->filters = array(
            'is_active'=>array(
                'label'=>'Все пользователи',
                'options'=>array(
                    'all'=>'Все пользователи',
                    \Users_m::STATUS_NOT_ACTIVATED=>'Не активированные',
                    \Users_m::STATUS_ACTIVATED=>'Активированные'
                )
            ),
            'group_alias'=>array(
                'label'=>'По группам',
                'options'=>array(
                    'all'=>'Все группы'
                )
            )
        );
        
        if( $groups = $this->groups_m->where('alias !=', 'guests')->get_all() )
        {
            foreach($groups as $group)
            {
                $this->filters['group_alias']['options'][$group->alias] = $group->name;
            }
        }        
    }
    
    function search()
    {
        $this->model->where('id !=', 0);
        
        return parent::search();
    }
}