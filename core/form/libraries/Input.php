<?php

namespace Form;

/**
 * Класс элемента формы с добавлением валидации и прочих плюшек
 */
class Input extends \Form\Input\Html
{
    /**
     * Требуется ли сохранять поле
     */
    public $save = TRUE;
    
    /**
     * Требуется ли фильтровать полученные данные поля
     */
    public $xss = TRUE;
    
    /**
     * Текст ошибки при валидации
     */
    public $error = 'Ошибка';
    
    /**
     * Правила валидации. 
     * 
     * При сложных правилах валидации, не трогать свойство. При этом,
     * при валидации формы, будет запущен метод validate(), в котором
     * и должен быть код валидации
     */
    public $rules;
    
    function init()
    {
        if( !$this->view )
        {
            $class_name = explode('\\', strtolower(get_class($this)));
            $this->view = $class_name[count($class_name) - 1];
        }
        
        if( strstr($this->view, '::') === FALSE )
        {
            $this->view = $this->form->inputs_folder . $this->view;
        }
        
        if( !$this->rules )
        {
            $this->rules = 'callback_validate_input['. $this->field .']';
        }
    }
    
    /**
     * Метод валидации. Сюда следует добавлять код валидации
     */
    function validate()
    {
        return true;
    }
    
    /**
     * Возвращает ошибку валидации
     */
    function get_error()
    {
        return $this->error;
    }
    
    function before_render(){}
}