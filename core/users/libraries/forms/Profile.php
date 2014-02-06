<?php

namespace Users\Forms;

/**
 * Форма редактирования профиля пользовеля
 */
class Profile extends \App\Form
{
    private $validator;
    
    function init()
    {
        parent::init();
        
        $this->validator = new Validator($this);
        
        $this->inputs = self::inputs();
    }
    
    /**
     * Элементы формы профиля
     * Для бекенда и фронтенда
     */
    static function inputs()
    {
        return array(
            'email'=>array(
                'type'=>'text',
                'label'=>'Почта',
                'rules'=>'trim|required|valid_email|callback_email_exists'
            ),
            'avatar'=>new \Users\Forms\Inputs\Avatar
        );
    }
    
    /**
     * Проверка почты
     */
    function email_exists($str)
    {        
        return $this->validator->email_exists($str);
    }
}