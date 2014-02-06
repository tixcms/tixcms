<?php

namespace Users;

class Notices
{
    function count()
    {
        \CI::$APP->load->model('users/users_m');
        
        return array(
            'count'=>\CI::$APP->users_m->by_is_moderated(0)->where('group_alias !=', 'guests')->count(),
            'label'=>'Новые пользователи'
        );
    }
}