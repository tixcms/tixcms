<?php

namespace SEO\Yandex;

class Metrica
{
    /**
     * 
     */
    protected $id;
    
    /**
     * Вебвизор
     * Запись и анализ поведения посетителей сайта.
     */
    protected $webvisor = false;
    
    /**
     * Карта кликов
     * Сбор статистики для работы отчёта «Карта кликов».
     */
    protected $clickmap = false;
    
    /**
     * Внешние ссылки, загрузки файлов и отчёт по кнопке «Поделиться»
     */
    protected $trackLinks = false;
    
    /**
     * Точный показатель отказов
     */
    protected $accurateTrackBounce = false;
    
    /**
     * Запрет отправки на индексацию страниц сайта.
     */
    protected $ut = false;
    
    /**
     * Отслеживание хеша в адресной строке браузера
     */
    protected $trackHash = false;
    
    
    public static function create($options = array())
    {
        return new self($options);
    }
    
    function __construct($options = array())
    {
        foreach($options as $key => $value)
        {
            $this->$key = $value;
        }
    }
    
    function render()
    {
        $options = $this->options();
        
        return \CI::$APP->template->view('seo::yandex/metrica', array(
            'id'=>$this->id,
            'options'=>json_encode($options)
        ));
    }
    
    protected function options()
    {
        $options['id'] = $this->id;
        
        $items = array('webvisor', 'clickmap', 'trackLinks', 'accurateTrackBounce', 'trackHash');
        
        foreach($items as $item)
        {
            if( $this->$item )
            {
                $options[$item] = TRUE;
            }
        }
        
        if( $this->ut )
        {
            $options['ut'] = "noindex";
        }
        
        return $options;
    }
}