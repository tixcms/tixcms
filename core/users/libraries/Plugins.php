<?php

namespace Users;

class Plugins extends \Tix\Plugin
{
    /**
     * Вывод данных текущего пользователя
     */
    function current($data = 'имя труемой переменной. login, email, register_date')
    {
        $data = $this->attribute('data');
        
        return $this->user->$data;
    }
}