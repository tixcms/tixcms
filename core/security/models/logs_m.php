<?php

class Logs_m extends Tix\Model
{
    public $table = 'security_logs';
    public $entity = 'Security\Entities\Log';
    
    function _relations()
    {
        return array(
            'users'=>array('users/users_m', 'user_id', 'user')
        );
    }
    
    const TYPE_LOGIN = 1;
    const TYPE_LOGOUT = 2;
    const TYPE_FAIL_LOGIN = 3;
}