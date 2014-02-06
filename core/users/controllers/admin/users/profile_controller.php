<?php

class Profile_Controller extends Users\Admin\Controller\Profile
{
    /**
     * Просмотр профиля пользователя
     */
    function action_view($id, $module = false)
    {
        if( !$user = $this->users_m->by_id($id)->get_one() )
        {
            show_404();
        }
        
        if( $module )
        {
            $this->view_module($module, $user);
            
            return;
        }
        
        $data = array(
            array(
                'label'=>'ID',
                'value'=>$user->id
            ),
            array(
                'label'=>'Логин',
                'value'=>$user->login
            ),
            array(
                'label'=>'Группа',
                'value'=>Users\Groups::label($user->group_alias)
            ),
            array(
                'label'=>'Email',
                'value'=>$user->email
            ),
            array(
                'label'=>'Дата регистрации',
                'value'=>Helpers\Date::nice($user->register_date)
            ),
            array(
                'label'=>'Потверждение почты',
                'value'=>$user->is_active ? 'да' : 'нет'
            )
        );
        
        $this->render(array(
            'user'=>$user,
            'data'=>$data,
            'action_view'=>true
        ));
    }
    
    function view_module($module, $user)
    {
        $class = ucfirst($module) .'\Users\Profile';
        
        if( !class_exists($class) )
        {
            show_404();
        }
        
        $class = new $class;
        
        $class->run($user);
    }
}