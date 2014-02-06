<?php

namespace Dashboard;

class ProfileForm extends \Users\Forms\Backend\Profile
{
    function init()
    {
        parent::init();
        
        $this->remove_input('id');
        $this->remove_input('avatar');
        $this->remove_input('is_active');
        $this->remove_input('register_date');
        $this->remove_input('group_alias');
        $this->remove_input('email');
        
        unset($this->actions['submit']);
        $this->actions['apply']['value'] = 'Сохранить';
    }
}