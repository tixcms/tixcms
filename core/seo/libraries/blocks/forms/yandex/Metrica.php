<?php

namespace SEO\Blocks\Forms\Yandex;

class Metrica extends \Block\Form
{
    function data()
    {
        return array(
            'id'=>array(
                'type'=>'text',
                'label'=>'Номер счетчика',
                'save'=>false,
                'rules'=>'trim|required',
                'help'=>'ID сайта (UA-1234567-1)'
            ),
            'webvisor'=>array(
                'type'=>'select',
                'label'=>'Вебвизор',
                'save'=>FALSE,
                'rules'=>'trim|required',
                'help'=>'Запись и анализ поведения посетителей сайта',
                'options'=>array(
                    1=>'Включен',
                    0=>'Выключен'
                )
            ),
            'clickmap'=>array(
                'type'=>'select',
                'label'=>'Карта кликов',
                'save'=>FALSE,
                'rules'=>'trim|required',
                'help'=>'Сбор статистики для работы отчёта «Карта кликов»',
                'options'=>array(
                    1=>'Включен',
                    0=>'Выключен'
                )        
            ),
            'trackLinks'=>array(
                'type'=>'select',
                'label'=>'Внешние ссылки',
                'save'=>FALSE,
                'rules'=>'trim|required',
                'help'=>'Внешние ссылки, загрузки файлов и отчёт по кнопке «Поделиться»',
                'options'=>array(
                    1=>'Включен',
                    0=>'Выключен'
                )        
            ),
            'accurateTrackBounce'=>array(
                'type'=>'select',
                'label'=>'Точный показатель отказов',
                'save'=>FALSE,
                'rules'=>'trim|required',
                'help'=>'',
                'options'=>array(
                    1=>'Включен',
                    0=>'Выключен'
                )        
            ),
            'ut'=>array(
                'type'=>'select',
                'label'=>'Запрет индексации',
                'save'=>FALSE,
                'rules'=>'trim|required',
                'help'=>'Запрет отправки на индексацию страниц сайта',
                'options'=>array(
                    1=>'Включен',
                    0=>'Выключен'
                )        
            ),
            'trackHash'=>array(
                'type'=>'select',
                'label'=>'Отслеживание хеша',
                'save'=>FALSE,
                'rules'=>'trim|required',
                'help'=>'Отслеживание хеша в адресной строке браузера',
                'options'=>array(
                    1=>'Включено',
                    0=>'Выключено'
                )        
            )
        );
    }
}