<?php

namespace Form\Input;

/**
 * Элемент выводит выпадающие списки для даты
 */
class Captcha extends \Form\Input
{
    /**
     * @var string Текст ошибки
     */
    public $error = 'Неверно введен проверочный текст';

    /**
     * Файл вида
     */
    public $view = 'captcha';

    /**
     * Лейбл
     */
    public $label = 'Проверочный код';

    public $value = '';

    /**
     * @var object Класс captcha
     */
    public $captcha;

    /**
     * @var bool Не требуется сохранять в БД
     */
    public $save = FALSE;

    function init()
    {
        parent::init();

        $this->captcha = new Captcha\SimpleCaptcha;
    }

    function current($field)
    {
        $map = array('year'=>'Y', 'month'=>'n', 'day'=>'j', 'hours'=>'H', 'minutes'=>'i');

        return date($map[$field]);
    }

    function validate($str)
    {
        if( $str != $_SESSION[$this->captcha->session_var] )
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
}