<?php

class Security_Controller extends Security\Controller
{
    function action_index()
    {
    	$this->redirect('admin/security/logs');

        $this->render();
    }
}