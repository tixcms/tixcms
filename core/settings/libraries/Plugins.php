<?php

namespace Settings;

class Plugins extends \Tix\Plugin
{
    /**
     * Вывод значения настройки
     */
    function item($id = 'id настройки')
    {
        $id = $this->attribute('id');

        return \CI::$APP->settings->$id;
    }
}