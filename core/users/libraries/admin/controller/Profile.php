<?php

namespace Users\Admin\Controller;

class Profile extends \Users\Admin\Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->template->add_layout('users::profile/layout');
        
        $this->template->set('tabs', $this->tabs($this->uri->segment(5)));
    }
}