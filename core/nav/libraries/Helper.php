<?php

namespace Nav;

class Helper
{
    static function type_options()
    {
        return array(
            \Nav_m::TYPE_URL=>'Простая ссылка',
            \Nav_m::TYPE_PAGE=>'Страница'
        );
    }
}