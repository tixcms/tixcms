<?php

class Security 
{
    static function csrf_generate_hash()
    {
        if ( \CI::$APP->session->userdata('csrf_token') )
		{
			return \CI::$APP->session->userdata('csrf_token');
		}
        
        $hash = md5(uniqid(rand(), true));

		\CI::$APP->session->set_userdata('csrf_token', $hash);

		return $hash;
    }
    
    static function check_csrf_token()
    {
        if( \CI::$APP->session->userdata('csrf_token')
            AND ( 
                    ( isset($_POST['csrf_token']) AND $_POST['csrf_token'] == \CI::$APP->session->userdata('csrf_token') )
                OR
                    ( isset($_GET['csrf_token']) AND $_GET['csrf_token'] == \CI::$APP->session->userdata('csrf_token') )
                ) 
        )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}