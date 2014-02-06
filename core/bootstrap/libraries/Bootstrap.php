<?php

class Bootstrap
{
    /**
     * Возвращает ссылки на css файлы
     */
    static function css($render = FALSE)
    {
        if( $render )
        {
            return CI::$APP->di->assets->render_css('bootstrap::bootstrap.min.css') ."\n".
                CI::$APP->di->assets->render_css('bootstrap::bootstrap-responsive.min.css');
        }
        else
        {
            CI::$APP->di->assets->css('bootstrap::bootstrap.min.css');
            CI::$APP->di->assets->css('bootstrap::bootstrap-responsive.min.css');
        }
    }
    
    /**
     * Возвращает ссылки на css файлы
     */
    static function js($render = FALSE)
    {
        if( $render )
        {
            return CI::$APP->di->assets->render_js('bootstrap::bootstrap.min.js');
        }
        else
        {
            CI::$APP->di->assets->js('bootstrap::bootstrap.min.js');
        }
    }
    
    /**
     * Возвращает ссылки на js, css
     */
    static function all($render = FALSE)
    {
        return self::css($render) . self::js($render);
    }
}