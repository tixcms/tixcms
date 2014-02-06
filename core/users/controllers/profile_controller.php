<?php

/**
 * Профиль пользователя
 */
class Profile_Controller extends Users\App\Controller\Profile
{    
    /**
     * Первая страница профиля пользователя
     */
    function action_index($data = false)
    {
        $this->render('profile/index', array(
            'tab'=>'general'
        ));
    }
}