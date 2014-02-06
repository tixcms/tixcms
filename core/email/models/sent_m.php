<?php

class Sent_m extends Tix\Model
{
    public $table = 'email_sent';
    public $entity = 'Email\Entities\Sent';
    
    function _relations() 
    {
        return array(
            'users'=>array('users/users_m', 'user_id', 'user')
        );
    }
}