<?php

namespace HTML;

class Table extends \HTML\TList
{
    /**
     * Массив с опциями заголовка таблицы
     */
    public $headings;
    
    /**
     * Шаблон таблицы
     */
    public $view = 'app::table';
    
    /**
     * Шаблон заголовка
     */
    public $view_head = 'app::table/head';
    
    /**
     * Шаблон фильтров
     */
    public $view_filters = 'app::table/filters';
    
    /**
     */
    public $view_per_page_options = 'app::table/per_page_options';
    
    /**
     * Здесь можно указать ключи заголовков, для которых будет включена сортировка
     */
    public $sortable_headings = array();
    
    /**
     * Определяет показывать ли таблицу при отсутствии записей
     * Нужно для правильно работы с ajax
     */
    public $show_table_with_no_items = false;
    
    /**
     * HTML аттрибуты таблицы
     */
    public $attrs;
    
    /**
     * Имя класса в шаблонах
     */
    public $class_name_in_view = 'table';
    
    /**
     * Данные по таблице для js
     */
    function get_json_data()
    {
        return array(
            'per_page'=>$this->per_page
        );
    }
    
    function sort()
    {
        $sort = $this->url_query->get('sort');
        
        if( $this->is_valid_sort_query($sort) )
        {
            list($order, $field) = $this->parse_sort_query($sort);
            
            $this->model->order_by($field, $order);
        }
        else
        {
            if( $this->default_sort )
            {
                $this->model->order_by($this->default_sort, '', false);
            }
        }
    }
    
    function is_valid_sort_query($str)
    {
        return in_array(substr($str, 0, -4), $this->sortable_headings)
                OR in_array(substr($str, 0, -5), $this->sortable_headings);
    }
    
    function parse_sort_query($str)
    {
        $order = substr($str, -3) == 'asc' ? 'ASC' : 'DESC';
        $field = $order == 'ASC' ? substr($str, 0, -4) : substr($str, 0, -5);
        
        return array(
            $order,
            $field
        );
    }
    
    function render_json()
    {
        return json_encode(array(
            'head'=>$this->render_head(),
            'body'=>$this->render_items(),
            'pager'=>$this->render_pager(),
            'per_page_options'=>$this->render_per_page_options(),
            'filters'=>$this->render_filters(),
            'total'=>$this->total,
            'data'=>json_encode($this->extra_data)
        ));
    }
    
    /**
     * Вывод заголовка таблицы
     */
    function render_head()
    {
        return $this->template->view($this->view_head, array(
            $this->class_name_in_view=>$this
        ));
    }
    
    /**
     * Вывод записей
     */
    function render_items()
    {
        if( $this->ajax AND $this->total == 0 )
        {
            return '<tr><td colspan="'. count($this->headings) .'">'. $this->no_items .'</td></tr>';
        }

        return parent::render_items();
    }    
    
    /**
     * Приведение массива значений заголовка к стандартному виду
     * 
     * Добавляются все необходимые опции
     */
    function normalize_headings()
    {        
        if( $this->headings )
        {            
            foreach($this->headings as $key=>$options)
            {                
                if( !is_array($options) )
                {
                    $this->headings[$key] = array(
                        'label'=>$options,
                        'sortable'=>false,
                        'searchable'=>false,
                        'attrs'=>''
                    );
                    
                    continue;
                }
                
                // sortable
                if( !isset($options['sortable']) )
                {
                    $this->headings[$key]['sortable'] = false;
                }
                else
                {
                    if( $options['sortable'] )
                    {
                        $this->sortable_headings[] = $key;
                    }
                }
                
                // searchable            
                if( isset($options['searchable']) AND $options['searchable'] )
                {
                    $this->searchable_fields[] = $key;
                }
                
                // attrs
                if( !isset($options['attrs']) )
                {
                    $this->headings[$key]['attrs'] = false;
                }
            }
        }
    }
    
    /**
     * Before_render
     */
    function before_render()
    {
        parent::before_render();
        
        // если есть какие-нибудь переданные параметры, то показываем даже пустую таблицу
        foreach($this->url_query_valid_keys as $key)
        {
            if( $this->url_query->get($key) )
            {
                $this->show_table_with_no_items = true;
            }
        }        
    }
    
    function init()
    {
        parent::init();
    }
    
    function post_init()
    {
        parent::post_init();
        
        $this->normalize_headings();
    }
}