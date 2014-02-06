<?php

namespace Users\Forms\Backend;

class Profile extends \Admin\Form
{
    protected $validator;
    public $fields_order = array('id', 'login', 'password', 'email', 'group_alias', 'register_date', 'is_active', 'avatar');
    
    function before_save()
    {
        $this->set('is_active', $this->get('is_active') ? \Users_m::STATUS_ACTIVATED : \Users_m::STATUS_NOT_ACTIVATED );
        
        // если пароль не был введен, то не сохраняем его
        if( $this->is_update() AND !$this->get('password') )
        {
            $this->un_set('password');
        }
        else
        {
            $this->set('password', $this->auth->dohash($this->get('password')));
        }
    }
    
    function init()
    {
        parent::init();
        
        $this->validator = new \Users\Forms\Validator($this);
        
        $inputs = array_merge(\Users\Forms\Profile::inputs($this), array(
            'id'=>array(
                'type'=>'text',
                'label'=>'ID',
                'visible'=>$this->is_update(),
                'attrs'=>array(
                    'disabled'=>'disabled',
                    'style'=>'width: 30px; text-align: center;'
                ),
                'save'=>FALSE
            ),
            'login'=>array(
                'type'=>'text',
                'label'=>'Логин',
                'rules'=>'trim|required|callback_login_exists'
            ),
            'password'=>array(
                'type'=>'password',
                'label'=>'Пароль',
                'value'=>$this->submitted() ? set_value('password') : '',
                'rules'=>$this->is_insert() ? 'trim|required' : ''
            ),
            'group_alias'=>array(
                'type'=>'select',
                'label'=>'Группа',
                'options'=>$this->groups(),
                'save'=>$this->is_insert() OR $this->entity->id != $this->user->id,
                'attrs'=>array(
                   $this->is_update() AND $this->entity->id == $this->user->id ? 'disabled' : 'enabled'=>''
                ),
                'rules'=>'trim|required'
            ),
            'is_active'=>new \Form\Input\Checkbox(array(
                'label'=>'Подтверждена почта'
            )),
            'register_date'=>new \Form\Input\DateTime(array(
                'label'=>'Дата регистрации'
            ))
        ));
        
        foreach($this->fields_order as $field)
        {
            $this->inputs[$field] = $inputs[$field];
            unset($inputs[$field]);
        }
        
        $this->inputs = array_merge($this->inputs, $inputs);
    }
    
    function groups()
    {
        $this->load->model('users/groups_m');
        
        // group options
        $groups = $this->groups_m->where('alias !=', 'guests')->get_all();
        foreach($groups as $group)
        {
            $groups_options[$group->alias] = $group->name;
        }
        
        return $groups_options;
    }
    
    /**
     * Проверка почты
     */
    function email_exists($str)
    {        
        return $this->validator->email_exists($str);
    }
    
    /**
     * Проверка логина
     */
    function login_exists($str)
    {
        return $this->validator->login_exists($str);
    }
}