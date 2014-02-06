<?php

namespace Form;

/**
 * Генератор форм
 */
class Html
{
    /**
     * Файл вида
     */
    public $view = 'app::form';
    
    /**
     * Папка с шаблонами элементов формы
     */
    public $inputs_folder = 'app::form/inputs/';
    
    /**
     * Элементы формы
     */
    public $inputs;
    
    /**
     * Шаблон управляющих элементов (сабмиты, ссылки)
     */
    public $actions_view = 'app::form/actions';

    /**
     * Управляющие кнопки формы
     */
    public $actions = array(
        'submit'=>array(
            'name'=>'submit',
            'attrs'=>array('class'=>"btn btn-primary ajax-submit"),
            'value'=>'Сохранить'
        )
    );

    /**
     * Аттрибуты формы
     */
    public $attrs = array();
    
    /**
     * Текст легенды формы
     */
    public $legend;
    
    /**
     * Превращены ли инпуты в обекты
     */
    private $initialized = FALSE;
    
    /**
     * Вызывается перед выводом формы
     */
    function before_render(){}
    
    /**
     * Создание экземпляра класса
     */
    static function create($options)
    {
        return new self($options);
    }
    
    protected function __construct($options = array())
    {
        if( $options )
        {
            foreach($options as $key=>$data)
            {
                $this->$key = $data;
            }
        }
    }
    
    /**
     * Превращение инпутов в объекты и загрузка конфига если не были 
     * указаны необходимые свойства
     */
    protected function initialized()
    {
        if( $this->initialized )
        {
            return;
        }
        
        // Превращаем инпуты в объекты
        if( $this->inputs )
        {
            foreach( $this->inputs as $name=>$input )
            {
                if( !is_object($input) )
                {
                    $this->inputs[$name] = (object)$input;
                }
            }
        }
    }
    
    /**
     * Вывод формы или ее элементов
     * 
     * @param string Тип элемента. FALSE - вывод все формы
     * @param array Дополнительные параметры
     * @return string
     */
    function render($type = false, $params = false, $more_params = false)
    {
        // превращаем инпуты в объекты
        if( !$this->initialized )
        {
            $this->initialized();
            
            $this->initialized = true;
        }
        
        // отрисовка отдельных элементов
        if( $type )
        {
            $method = 'render_'. $type;
            
            return $this->$method($params, $more_params);
        }
        // вывод всей формы
        else        
        {
            $this->before_render();
            
            return $this->template->view($this->view, array(
                'form'=>$this
            ));
        }
    }
    
    /**
     * Вывод поля формы
     * 
     * @param string имя поля
     * @return string
     */
    function render_input($name)
    {
        $input = $this->inputs[$name];
        
        if( isset($input->visible) AND $input->visible == FALSE )
        {
            return;
        }
        
        if( $input->rendered )
        {
            return;
        }
        
        if( method_exists($input, 'before_render') )
        {
            $input->before_render();
        }
        
        $input->field = $name;
        $input->attrs = isset($input->attrs) ? $input->attrs : FALSE;
        $input->help = isset($input->help) ? $input->help : FALSE;
        $input->value = isset($input->value) ? $input->value : '';
        
        $view = isset($input->view) ? $input->view : $this->inputs_folder . $input->type;

        // отмечаем, что инпут был выведен, чтобы не выводить повторно
        $this->inputs[$name]->rendered = true;
        
        return $this->template->view($view, $input);
    }
    
    /**
     * Вывод управляющих элементов
     * 
     * @return string
     */
    protected function render_actions()
    {
        return $this->template->view($this->actions_view, array(
            'actions'=>$this->actions
        ));
    }
    
    /**
     * Вывод подсказки по полям формы
     * 
     * @return string
     */
    protected function render_help($items = FALSE)
    {
        $help = ''; 
        
        if( !$items )
        {
            if( $this->inputs )
            {
                foreach($this->inputs as $key => $input)
                {
                    if( isset($input->help) AND $input->help )
                    {
                        $help .= '<p><strong>'. $input->label .'</strong>';
                        $help .= $input->help .'</p>';
                    }
                }
            }
            
            return $help ? $help : 'Нет информации';
        }
        else
        {
            if( is_array($items) )
            {
                foreach($items as $item)
                {
                    $help .= $this->help($item);
                }
            }
            else
            {
                $field = $items;
                
                if( isset($this->inputs[$field]) )
                {
                    $help .= '<p><strong>'. $this->inputs[$field]->label .'</strong>';
                    $help .= $this->inputs[$field]->help .'</p>';
                }
            }
            
            return $help;
        }
    }
    
    /**
     * Вывод полей формы
     * Выводит все поля, либо указанные
     * 
     * @param array Массив имен полей для вывода.
     * @return string
     */
    function render_inputs($names = array(), $exclude_inputs = array())
    {
        $inputs = '';
        if( $this->inputs )
        {            
            foreach( $this->inputs as $name=>$input )
            {
                if( is_array($exclude_inputs) AND in_array($name, $exclude_inputs) )
                {
                    continue;
                }
                
                if( !$input->rendered AND ( empty($names) OR in_array($name, $names) ) )
                {
                    $inputs .= $this->render_input($name);
                }
            } 
        }
        
        return $inputs;
    }
    
    /**
     * Вывод аттрибутов формы
     * 
     * @return string
     */
    protected function render_attrs()
    {        
        // экшен формы
        if( !isset($this->attrs['action']) )
        {
            $this->attrs['action'] = '';
        }
        
        // метод отправки данных       
        if( !isset($this->attrs['method']) )
        {
            $this->attrs['method'] = 'post';
        }
        
        // аттрибуты для загрузки файлов
        if( isset($this->attrs['upload']) AND $this->attrs['upload'] == true )
        {
            $this->attrs['enctype'] = 'multipart/form-data';
            unset($this->attrs['upload']);
        }
        
        return \HTML\Tag::parse_attributes($this->attrs);
    }

    /**
    * Метод сбрасывает флаг инпутов, что они уже были отображены
    */
    function reset()
    {
        foreach($this->inputs as $field=>$input)
        {
            if($input->rendered)
            {
                $this->inputs[$field]->rendered = FALSE;
            }
        }
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}