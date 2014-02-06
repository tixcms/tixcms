<?php

namespace Tix\Template;

class Native implements AdapterInterface
{
    function view($view, $data, $templates_dirs = array())
    {
        return \CI::$APP->load->view($view, $data, true, $templates_dirs);
    }
}