<?php

namespace Form\Input;

/**
 * Абстрактный класс элемента формы
 */
abstract class Html
{
    /**
     * Файл вида
     */
    public $view;
    
    /**
     * Лейбл
     */
    public $lable;
    
    /**
     * Аттрибуты инпута
     */
    public $attrs = array();
    
    /**
     * Значение
     */
    public $value;
    
    /**
     * Справочная подпись
     */
    public $help = FALSE;
    
    /**
     * Имя поля
     */
    public $field;
    
    /**
     * Был ли уже выведен инпут
     */
    public $rendered;
    
    /**
     * В конструкторе возможно переопределить свойства
     */
    function __construct($properties = array())
    {
        foreach($properties as $name => $value)
        {
            $this->$name = $value;
        }
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}