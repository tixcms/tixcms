<?php

/**
 * Класс для вывода RSS
 * 
 * Пример кода
 * 
 * $Rss = new Rss;
 * 
 * // какие-то данные
 * $items = $this->some_m->get_all();
 *
 * $rss->set_title('Новости'); // название
 * $rss->set_link('http://example.com'); // ссылка
 * $rss->set_description('Мировые новости'); // описание
 * $rss->set_lang('ru'); // язык, по-умолчанию "ru"
 * 
 * либо установите глобальные значения в rss/config/config.php
 * 
 * $rss->set_items($items); // данные
 * 
 * В данных должны быть следующие ключи (массив объектов или массивов)
 * 
 * title - заголовок
 * link - ссылка
 * description - описание
 * date - дата в timestamp
 * category - категория // не обязательно
 * author - автор // не обязательно
 */

class Rss
{
    /**
     * Шаблон
     */
    private $view = 'rss::rss';
    
    /**
     * Заголовок
     */
    private $title;
    
    /**
     * Ссылка
     */
    private $link;
    
    /**
     * Описание
     */
    private $description;
    
    /**      
     * Данные
     * @param array
     */
    private $items;
    
    /**
     * Язык текста
     */
    private $lang = 'ru';
    
    /**
     * Конструктор
     */
    function __construct()
    {
        // загружаем конфиг
        CI::$APP->load->config('rss/config');
    }
    
    /**
     * Устанавливает значения
     */
    private function set_options()
    {
        $this->title = $this->title ? $this->title : CI::$APP->config->item('rss_title');
        $this->description = $this->description ? $this->description : CI::$APP->config->item('rss_description');
        $this->link = $this->link ? $this->link : CI::$APP->config->item('rss_link');
        $this->lang = $this->lang ? $this->lang : CI::$APP->config->item('rss_lang');
    }
    
    /**
     * Setter
     */
    function __set($name, $value)
    {
        $this->$name = $value;
    }
    
    /**
     * Вывод RSS
     */
    function render()
    {
        // устанавливаем значения
        $this->set_options();
        
        // отправляем заголовок
        //header("Content-Type: application/xml; charset=UTF-8");
        
        if( !$this->items )
        {
            return;
        }
        
        header('Content-Type: application/rss+xml');

        return \CI::$APP->template->view($this->view, array(
            'title'=>$this->title,
            'link'=>$this->link ? $this->link : URL::base_url(),
            'description'=>$this->description,
            'lang'=>$this->lang,
            'items'=>(object)$this->items
        ));
    }
}