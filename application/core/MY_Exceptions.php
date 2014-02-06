<?php

class MY_Exceptions extends CI_Exceptions {
    
    function show_404($page = '', $log_error = true)
    {
        echo Modules::run('pages/errors/action_404');
    }
}