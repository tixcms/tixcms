<?php

namespace Users\App\Controller;

class Profile extends \Users\App\Controller
{
    function __construct()
    {
        parent::__construct();
        
        // устанавливаем вложенный layout
        $this->template->add_layout('profile/layout');
        
        // присваиваем переменной
        $this->template->set('user', $this->user);
        
        // стили
        $this->di->assets->css('profile.css');
        
        // хлебные крошки
        $this->crumb('Личный профиль', 'users/profile');
    }
}