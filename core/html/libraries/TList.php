<?php

namespace HTML;

class TList 
{
    /**
     * Шаблон списка
     */
    public $view = 'app::tlist';
    
    /**
     * Шаблон сортировки
     */
    public $view_sorts = 'app::tlist/sorts';
    
    /**
     * Шаблон сортировки
     */
    public $view_filters = 'app::tlist/filters';
    
    /**
     * Шаблон записи
     */
    public $item_view;
    
    /**
     * Всего записей
     */
    public $total;
    
    /**
     * Записей на страницу
     * 
     * Если false, то без пагинации
     */
    public $per_page = false;
    
    /**
     * Класс пагинации
     */
    public $pager;
    
    /**
     * Шаблон пагинации, если требуется не дефолтный
     */
    public $pager_view = false;
    
    /**
     * Текущая страница
     */
    public $current_page = false;
    
    /**
     * Модель
     */
    public $model;
    
    /**
     * Дефолтная сортировка
     */
    public $default_sort = false;
    
    /**
     * 
     */
    public $url_query;
    
    /**
     */
    public $url_query_valid_keys = array('sort', 'search', 'filter', 'page', 'per_page');
    
    /**
     * Фильтры
     */
    public $filters = array();
    
    /**
     * Записи
     */
    public $items;
    
    /**
     * Текущий урл
     */
    public $current_url;
    
    /**
     * Сортировка
     */
    public $sorts = array();
    
    /**
     */
    public $searchable_fields = array();
    
    /**
     * Ajax вывод
     */
    public $ajax = false;
    
    /**
     * Условия выбора записей
     */
    public $where = array();
    
    /**
     * JS script для ajax
     */
    public $js_script = 'app::tlist.js';
    
    /**
     */
    public $search = 'auto';
    
    /**
     * Опции выбора количества записей на страницу
     */
    public $per_page_options = array(10, 20, 50, 100);
    
    /**
     * Имя класса в шаблонах
     */
    public $class_name_in_view = 'list';
    
    /**
     * минимальное количество записей для показа поисковой строки
     */
    public $min_total_to_show_search = null;
    
    /**
     * Дополнительные данные
     */
    public $extra_data = array();
    
    /**
     * Конструктор
     */
    function __construct($params = array())
    {
        // заполнение параметров
        if( $params )
        {
            foreach($params as $key=>$value)
            {
                $this->$key = $value;
            }
        }
        
        $this->init();
        
        // класс для работы с query строкой
        $this->url_query = $this->get_url_query();
        
        $this->post_init();
        
        // проверяем, что указан шаблон для записи
        if( !$this->item_view )
        {
            trigger_error('Не определен параметр $item_view');
            
            exit;
        }
        
        $this->adjust_per_page_options();
        
        // Определяем параметр количества записей на страницу
        $this->adjust_per_page();        
        
        // записи
        if( $this->model AND !$this->items )
        {
            list($this->total, $this->items) = $this->get_items_data();
        }
        else
        {
            $this->total = $this->items ? count($this->items) : 0;
        }
        
        // пагинация
        if( $this->per_page )
        {
            if( !$this->current_url )
            {
                $this->current_url = $this->di->url->uri_string();
            }
            
            $this->pager = $this->get_pager();
        }
    }
    
    /**
     * В опции выбора количества записей на страницу
     * добавляем указанное в классе свойство per_page
     * если не равно уже имеющимся
     */
    function adjust_per_page_options()
    {
        if( !in_array($this->per_page, $this->per_page_options) )
        {
            $this->per_page_options[] = $this->per_page;
            sort($this->per_page_options);
        }
    }
    
    /**
     * Определяем параметр количества записей на страницу
     * 
     * Может быть определен в классе, а может быть передан через 
     * строку браузера
     */
    function adjust_per_page()
    {
        if( $this->url_query->get('per_page') > 0 )
        {
            $this->per_page = (int)$this->url_query->get('per_page');
        }
    }
    
    /**
     * Возвращает класс пагинации
     */
    function get_pager()
    {
        $pager = $this->di->get('pager');
            
        $pager->set_page($this->current_page);
        $pager->set_total($this->total);
        $pager->set_url($this->current_url, $this->url_query);
        $pager->set_per_page($this->per_page);
        
        if( $this->pager_view )
        {
            $pager->set_view($this->pager_view);
        }
        
        return $pager;
    }
    
    function get_extra_data()
    {
        return array_merge($this->extra_data, array(
            'per_page'=>$this->per_page
        ));
    }
    
    /**
     * Вовзращает класс url_query
     */
    function get_url_query()
    {
        if( $this->filters )
        {
            foreach($this->filters as $key=>$filter)
            {
                $this->url_query_valid_keys[] = $key;
            }
        }
        
        return new \URL\Query($this->url_query_valid_keys);
    }
    
    /**
     * Вовзращает номер текущей страницы для пагинации
     */
    function get_current_page()
    {
        if( $this->current_page )
        {
            return $this->current_page;
        }
        else
        {
            if( $this->per_page )
            {
                $page = $this->url_query->get('page');
                return $page > 1 ? (int)$page : 1;
            }
            else
            {
                return false;
            }
        }
    }
    
    /**
     * Количество данных и сами данные
     */
    function get_items_data()
    {
        $this->db->start_cache();
        
            if( $this->where )
            {
                $this->model->where($this->where);
            }
            
            $this->filter();
            $this->search();
            
        $this->db->stop_cache();
        
            $total = $this->model->count();
  
            $this->sort();
            
            if( $this->per_page )
            {
                $this->current_page = $this->get_current_page();
                
                $offset = ($this->current_page - 1)*$this->per_page;
                
                $this->model->limit($this->per_page);
                $this->model->offset($offset);
            }
            
            $items = $this->get_items();
            
        $this->db->flush_cache();
        
        return array($total, $items);
    }
    
    /**
     * Получение записей
     */
    function get_items()
    {
        return $this->model->get_all();
    }
    
    /**
     * Добавление условия для выбора записей при поиске
     */
    function search()
    {
        $valid_fields = $this->searchable_fields;
        
        if( $search = $this->url_query->get('search') AND $valid_fields )
        {
            $sql = array();
            foreach($valid_fields as $field)
            {
                $sql[] = "{$this->model->table}.$field LIKE ". $this->db->escape('%'. $search . '%');
            }
            
            $this->model->where("(" . implode(' OR ', $sql) . ")", '', false);
        }
    }
    
    /**
     * Добавление сортировки
     */
    function sort()
    {        
        if( $this->sorts )
        {
            $valid_values = array_keys($this->sorts);
            $sort = $this->url_query->get('sort');
            
            if( in_array(substr($sort, 0, -4), $valid_values)
                OR in_array(substr($sort, 0, -5), $valid_values) )
            {
                $order = substr($sort, -3) == 'asc' ? 'ASC' : 'DESC';
                $field = $order == 'ASC' ? substr($sort, 0, -4) : substr($sort, 0, -5);
                
                $this->model->order_by($field, $order);
                
                return;
            }
        }
        
        if( $this->default_sort )
        {
            $this->model->order_by($this->default_sort, '', false);
        }
    }
    
    /**
     * Обработка фильтров
     */
    function filter()
    {
        if( $this->filters )
        {
            foreach($this->filters as $key=>$filter)
            {
                /**
                 * Иногда нужно не применять некоторые фильтры
                 * для этого в фильтре можно указать опцию skip = false
                 */
                if( !isset($filter['skip']) OR !$filter['skip'] )
                {
                    if( $value = $this->url_query->get($key) )
                    {
                        /**
                         * При передаче значению all, фильтрация не производится
                         */
                        if( $value != 'all' )
                        {
                            $this->model->where($this->model->table .'.'. $key, $value);
                        }

                        /**
                         * Значение нуль воспринимается как false и не обработывается
                         * Вместо нуля можно использовать off
                         */ 
                        if( $value == 'off' )
                        {
                            $this->model->where($this->model->table .'.'. $key, 0);
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Вывод таблицы
     */
    function render($format = 'html')
    {
        $this->before_render();
        
        if( $format == 'json' )
        {
            return $this->render_json();
        }
        else
        {
            return $this->render_html();
        }
    }
    
    function render_html()
    {
        if( $this->ajax AND $this->js_script )
        {            
            if( is_array($this->js_script) )
            {
                $this->di->assets->js($this->js_script[0], '', $this->js_script[1]);
            }
            else
            {
                $this->di->assets->js($this->js_script);
            }
        }
        
        return $this->template->view($this->view, array(
            $this->class_name_in_view=>$this,
            'data'=>$this->get_extra_data()
        ));
    }
    
    function render_json()
    {
        return json_encode(array(
            'items'=>$this->render_items(),
            'pager'=>$this->render_pager(),
            'per_page_options'=>$this->render_per_page_options(),
            'filters'=>$this->render_filters(),
            'total'=>$this->total,
            'data'=>json_encode($this->get_extra_data())
        ));
    }
    
    /**
     * Вывод пагинации
     */
    function render_pager()
    {
        if( $this->per_page )
        {
            return $this->pager->render();
        }
    }
    
    /**
     * Вывод шаблона сортировки
     */
    function render_sorts()
    {
        return $this->template->view($this->view_sorts, array(
            $this->class_name_in_view=>$this
        ));
    }
    
    /**
     * Вывод выбора количества записей на страницу
     */
    function render_per_page_options()
    {
        if( $this->per_page AND ( $this->total > $this->per_page OR $this->url_query->get('per_page') > 0 ) )
        {
            return $this->template->view($this->view_per_page_options, array(
                $this->class_name_in_view=>$this
            ));
        }
    }
    
    function render_filters()
    {
        return $this->template->view($this->view_filters, array(
            $this->class_name_in_view=>$this
        ));
    }
    
    /**
     * Вывод записей
     */
    function render_items()
    {
        $i=0;
        if( $this->total > 0 AND $this->items )
        {
            $result = '';
             
            foreach($this->items as $key=>$item)
            {                
                $is_first = $i == 0;
                $is_last = $i == ($this->total - 1);

                $result .= $this->template->view($this->item_view, array(
                    'i'=>$i,
                    'total'=>$this->total,
                    'is_first'=>$is_first,
                    'is_last'=>$is_last,
                    'item'=>$item,
                    'item_key'=>$key,
                    $this->class_name_in_view=>$this
                ));
                $i++;
            }
            
            return $result;
        }
        else
        {
            return $this->no_items;
        }
    }
    
    /**
     * Init for user class
     */
    function init(){}
    function post_init(){}
    
    function before_render()
    {
        // минимальное количество записей для показа поисковой строки
        if( $this->min_total_to_show_search === null AND $this->search === 'auto' )
        {
            if( $this->total > $this->per_page )
            {
                $this->search = true;
            }
        }
    }
    
    static function create($params = array())
    {
        return new static($params);
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}