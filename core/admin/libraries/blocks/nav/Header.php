<?php

namespace Admin\Blocks\Nav;

class Header extends \Block
{
    function data()
    {
        return array(
            'groups'=>\Modules\Helper::get_groups(),
            'modules_by_groups'=>\Modules\Helper::get_modules_by_groups()
        );
    }
}