<?php

namespace Admin;

class Table extends \HTML\Table
{
    /**
     * Файл шаблона всей таблицы, с пагинацией, фильтрами и т.д.
     */
    public $view = 'admin::table';
    
    /**
     * Файл шаблона заголовока таблицы
     */
    public $view_head = 'admin::table/head';
    
    /**
     * Файл шаблона заголовока таблицы
     */
    public $view_filters = 'admin::table/filters';
    public $view_filters_items = 'admin::table/filters/';
    
    /**
     * Файл шаблона выбора количества записей на страницу
     */
    public $view_per_page_options = 'admin::table/per_page_options';
    
    /**
     * Показывать border таблицы
     */
    public $bordered = true;
    
    /**
     * Показывать счетчик записей
     */
    public $show_total_counter = true;
    
    public $mass_actions_view = false;
    
    /**
     * Скрипт
     */
    public $js_script = array('admin::table.js', '1');
    
    function before_render()
    {
        parent::before_render();
        
        // ajax form
        $this->di->assets->js('jquery::plugins/jquery.form.js');
        
        // table styles
        $this->attrs['class'] = 'table '. ($this->bordered ? ' table-bordered' : '');
     
        if( $this->total == 0 )
        {
            foreach($this->url_query_valid_keys as $key)
            {
                if( $this->url_query->get($key) !== false )
                {
                    break;
                }
                
                $this->show_total_counter = false;
            }
        }
    }
    
    function render_mass_actions()
    {
        if( $this->mass_actions_view )
        {
            return $this->template->view($this->mass_actions_view);
        }
    }
}