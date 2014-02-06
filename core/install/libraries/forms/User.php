<?php

namespace Install\Forms;

class User extends \Form
{
    public $csrf_protection = false;
    public $inputs = array(
        'login'=>array(
            'type'=>'text',
            'label'=>'Имя пользователя',
            'rules'=>'trim|required'
        ),
        'email'=>array(
            'type'=>'text',
            'label'=>'Адрес почты',
            'rules'=>'trim|required|valid_email'
        ),
        'password'=>array(
            'type'=>'password',
            'label'=>'Пароль',
            'rules'=>'trim|required'
        ),
        'password_retype'=>array(
            'type'=>'password',
            'label'=>'Повтор пароля',
            'rules'=>'trim|required|matches[password]'
        ),
    );
    
    public $actions_view = 'forms/actions';
    
    public $attrs = array(
        'class'=>'form-horizontal'
    );

    function save()
    {
        $auth = $this->load->library('Users\Auth');
        
        $this->load->database();
        
        $this->db->insert('users', array(
            'login'=>$this->get('login'),
            'group_alias'=>'admins',
            'password'=>$auth->dohash($this->get('password')),
            'email'=>$this->get('email'),
            'register_date'=>time(),
            'lastvisit_date'=>time(),
            'is_active'=>1
        ));
    }
}