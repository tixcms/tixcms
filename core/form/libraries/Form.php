<?php

/**
 * Класс для создания формы, валидации и сохранения результата
 */
class Form extends Form\Html
{
    /**
     * Сохраняемый объект. При создании указать false
     */
    public $entity;
    
    /**
     * Модель сохраняемого объекта
     */
    public $model;
    
    /**
     * Класс валидации
     */
    public $form_validation;
    
    /**
     * Данные
     */
    protected $post_data;
    
    /**
     * Вывод ошибок по полям
     */
    protected $inline_errors = false;
    
    /**
     * Ajax
     */
    protected $ajax = false;
    
    /**
     * CSRF Protection
     */
    protected $csrf_protection = true;
    
    /**
     * Была ли валидация
     */
    protected $validated = false;

    /**
     * @param array
     */
    protected $response_message = array(
        'type'=>'',
        'text'=>''
    );

    protected $validated_successfully = null;
    
    /**
     * Класс валидации
     */
    protected $validation_class = false;

    static function create($options = array())
    {
        return new static($options);
    }
    
    function __construct($params = array())
    {        
        if( $params )
        {
            foreach($params as $key=>$data)
            {
                $this->$key = $data;
            }
        }
        
        // класс валидации
        $this->form_validation = new \Tix\Validation;
        
        // метод, где можно изменить значения свойств формы
        $this->init();
        
        // Cross Site Request Forgery
        if( $this->csrf_protection )
        {
            $this->add_csrf_field();
        }
        
        // приведение инпутов к единому виду
        $this->initialized();
        
        // добавляем необходимые свойства к объектам инпутов
        if( $this->inputs )
        {
            foreach($this->inputs as $name=>$input)
            {
                if( isset($this->inputs[$name]->visible) AND !$this->inputs[$name]->visible )
                {
                    unset($this->inputs[$name]);
                    continue;
                }
                
                $this->inputs[$name]->form = $this;
                $this->inputs[$name]->field = $name;
                $this->inputs[$name]->xss = isset($input->xss) ? $input->xss : true;
                $this->inputs[$name]->save = isset($input->save) ? $input->save : true;
                $this->inputs[$name]->rendered = false;
                //$this->inputs[$name]->error = false;
                
                // вызываем метод init() у инпутов, если есть
                if( method_exists($this->inputs[$name], 'init') )
                {
                    $this->inputs[$name]->init();
                }
            }
        }
    }
    
    protected function init(){} // метод, где можно изменить значения свойств формы
    protected function before_save(){} // вызывается до обновления или добавления
    protected function before_update(){} // вызывается до обновления
    protected function before_insert(){} // вызывается до добавления
    protected function after_save(){}
    protected function after_insert(){}
    protected function after_update(){}
    protected function set_response_message(){}
    
    /**
     * Валидация формы
     */
    function validate()
    {
        if( $this->inputs )
        {
            $this->set_inputs_rules();
            
            $rules = array();

            foreach($this->inputs as $key=>$input)
            {
                $rules[] = array(
                    'label'=>isset($input->label) ? $input->label : '',
                    'rules'=>$input->rules,
                    'field'=>$input->field
                );
            }
        
            // передаем правила валидации в класс валидации
            $this->form_validation->set_rules($rules);
            
            // помещаем POST данные во внутренний массив
            $this->set_post_data();
        }
        
        $this->validated = true;

        $this->validated_successfully = $this->form_validation->run($this, '', $this->validation_class);

        return $this->validated_successfully;
    }
    
    function before_render()
    {
        $this->set_inputs_value();
    }
    
    function add_csrf_field()
    {
        $this->inputs['csrf_token'] = array(
            'type'=>'hidden',
            'label'=>'csrf',
            'value'=>\Security::csrf_generate_hash(),
            'save'=>false,
            'rules'=>'callback_csrf_verify'
        );
    }
    
    function csrf_verify()
    {
        if( \Security::check_csrf_token() )
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('csrf_verify', 'CSRF error');
            
            return false;
        }
    }
    
    function set_validation_class($class)
    {
        $this->validation_class = $class;
    }
    
    /**
     * Возвращает ошибки валидации
     */
    function get_errors($type = 'string')
    {
        if( $type == 'string' )
        {
            // меняем делимитеры при ajax вызове
            if( $this->input->is_ajax_request() )
            {
                $this->form_validation->set_error_delimiters('', '');
            }
            
            return $this->form_validation->error_string();
        }
        else if( $type == 'array' )
        {
            $errors = array();
            foreach($this->inputs as $field=>$input)
            {
                $errors[] = array(
                    'field'=>$field,
                    'error'=>$this->form_validation->error($field)
                );
            }
            
            return $errors;
        }
    }
    
    function error($field)
    {
        return $this->form_validation->error($field);
    }
    
    /**
     * Проверка были ли отправлены дынные формы
     */
    function submitted($name = false)
    {
        if( $name )
        {
            return \CI::$APP->input->post($name);
        }
        else
        {
            return \CI::$APP->input->post();
        }
    }
    
    /**
     * Метод присваивает значения полям
     */
    protected function set_inputs_value()
    {
        // проставляем value
        if( $this->inputs )
        {
            foreach($this->inputs as $name=>$input)
            {
                if( !isset($this->inputs[$name]->value) )
                {
                    $this->inputs[$name]->value = $this->entity
                        ? (isset($this->entity->{$this->inputs[$name]->field}) 
                            ? $this->entity->{$this->inputs[$name]->field}
                            : '')
                        : $this->form_validation->set_value($this->inputs[$name]->field);
                }
            }
        }
    }
    
    /**
     * Устанавливаем дефолтные правила полям, у которых они не указаны
     * 
     * Правила по-умолчанию: trim
     */
    protected function set_inputs_rules()
    {
        // проставляем value
        if( $this->inputs )
        {
            foreach($this->inputs as $name=>$input)
            {
                if( !isset($this->inputs[$name]->rules) )
                {
                    $this->inputs[$name]->rules = 'trim';
                }
            }
        }
    }
    
    /**
     * Сохраняем данные полученные из формы
     * 
     * Свойство поля xss определяет будет ли обработка формы от вредного кода
     */
    protected function set_post_data()
    {
        if( $this->inputs )
        {
            foreach($this->inputs as $input)
            {
                if( !isset($input->save) OR $input->save != false )
                {                    
                    $this->post_data[$input->field] = \CI::$APP->input->post(
                        $input->field,
                        $input->xss
                    );
                }
            }
        }
    }
    
    /**
     * Adding input element
     * 
     * @param string Input identifier and field name
     * @param mixed Array or object
     */
    public function add_input($name, $input)
    {
        if( isset($this->inputs[$name]->visible) AND !$this->inputs[$name]->visible )
        {
            unset($this->inputs[$name]);
            continue;
        }
        
        $this->inputs[$name] = new stdClass;
        
        $this->inputs[$name]->form = $this;
        $this->inputs[$name]->field = $name;
        $this->inputs[$name]->xss = isset($input->xss) ? $input->xss : true;
        $this->inputs[$name]->save = isset($input->save) ? $input->save : true;
        $this->inputs[$name]->rendered = false;
        
        // вызываем метод init() у инпутов, если есть
        if( method_exists($this->inputs[$name], 'init') )
        {
            $this->inputs[$name]->init();
        }
        
        $this->inputs[$name] = $input;
    }
    
    /**
     * Adding input element options
     * 
     * @param string Input identifier
     * @param array Options
     */
    public function edit_input($name, $options)
    {
        foreach($options as $key=>$value)
        {
            if( is_object($this->inputs[$name]) )
            {
                $this->inputs[$name]->$key = $value;
            }
            else
            {
                $this->inputs[$name][$key] = $value;
            }            
        }
    }
    
    /**
     * Addings inputs
     * 
     * @param array
     */
    public function add_inputs($inputs)
    {
        foreach($inputs as $name=>$input)
        {
            $this->add_input($name, $input);
        }
    }
    
    /**
     * Removing input
     * 
     * @param string Name of input
     */
    public function remove_input($name)
    {
        unset($this->inputs[$name]);
    }
    
    /**
     * Устанавливаем данные
     */
    function set($item, $value)
    {
        if( !isset($this->inputs[$item]) OR (isset($this->inputs[$item]->save) AND $this->inputs[$item]->save) )
        {
            $this->post_data[$item] = $value;
        }
    }
    
    function un_set($item)
    {
        if( isset($this->post_data[$item]) )
        {
            unset($this->post_data[$item]);
        }
    }
    
    /**
     * Возвращает фильтрованное значение, если была установлена фильтрация поля
     */
    function get($name)
    {
        return isset($this->post_data[$name]) ? $this->post_data[$name] : false;
    }
    
    /**
     * Возвращает нефильтрованное значение поля
     */
    function post($key)
    {
        return \CI::$APP->input->post($key);
    }
    
    /**
     * Обновляем данные?
     */
    function is_update()
    {
        return !!$this->entity;
    }
    
    /**
     * Добавляем данные?
     */
    function is_insert()
    {
        return !$this->entity;
    }
    
    /**
     * Сохраняет данные
     */
    function save()
    {
        if( !$this->validated )
        {
            if( !$this->validate() )
            {
                $this->set_response_message();

                return false;
            }
        }
        
        $this->before_save();
        
        if( $this->is_update() )
        {
            $this->before_update();
            
            $this->update();
            
            $this->after_update();
        }
        else
        {
            $this->before_insert();

            $this->insert();
            
            $this->after_insert();
        }
        
        $this->trigger_after_save();

        $this->set_response_message();
        
        return true;
    }
    
    function trigger_after_save()
    {
        $this->after_save();
        
        foreach($this->inputs as $input)
        {
            if( method_exists($input, 'after_save') )
            {
                $input->after_save();
            }
        }
    }
    
    function update()
    {
        $pk = $this->model->primary_key;
            
        $this->model->where($pk, $this->entity->$pk)->update($this->post_data);
    }
    
    function insert()
    {
        $this->insert_id = $this->model->insert($this->post_data);
    }
    
    public function show_inline_errors()
    {
        return $this->inline_errors;
    }
    
    /**
     * Правило валидации, которое будет автоматом вызываться у всех
     * объектов класса Form\Input
     */
    function validate_input($value, $field)
    {
        if( $this->inputs[$field]->validate($value)  )
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('validate_input', $this->inputs[$field]->error);
            
            return false;
        }
    }

    /**
     * Возвращает сообщение о результате и его тип (success, error)
     */
    function get_response_message()
    {
        return $this->response_message;
    }
}