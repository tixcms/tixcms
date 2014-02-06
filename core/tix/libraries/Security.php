<?php

namespace Tix;

class Security extends \CI_Security
{
    function xss_clean($str, $is_image = FALSE)
    {
        return str_replace(array('{{', '}}'), array('&#123;&#123;', '&#125;&#125;'), parent::xss_clean($str, $is_image));
    }
}