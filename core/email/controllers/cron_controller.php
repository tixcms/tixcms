<?php

class Cron_Controller extends Tix\Controllers\CLI
{    
    function action_send()
    {
        $this->load->library('Tix\Email');
        $this->load->database();

        $email_sends = 5;

        $this->email->send_queue($email_sends);

        echo $email_sends .' emails successfully sent';
    }
}