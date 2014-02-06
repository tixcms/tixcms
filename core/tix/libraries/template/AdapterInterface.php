<?php

namespace Tix\Template;

interface AdapterInterface
{
    function view($view, $data, $templates_dirs);
}