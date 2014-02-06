<?php

namespace Form\Generated;

class Add extends \Admin\Form
{
    public $ajax = true;
    
    public function init()
    {
        parent::init();
        
        $this->set_inputs();
    }
    
    public function tabs()
    {
        return array(
            'main'=>'Общее',
            'inputs'=>'Поля'
        );
    }
    
    public function set_inputs()
    {
        $this->add_input('name', array(
            'type'=>'text',
            'label'=>'Название',
            'rules'=>'trim|required'
        ));
        
        $this->add_input('alias', array(
            'type'=>'text',
            'label'=>'Идентификатор',
            'rules'=>'trim|required|alpha_dash'
        ));
        
        $this->add_input('email', array(
            'type'=>'text',
            'label'=>'Email',
            'rules'=>'trim|valid_email',
            'attrs'=>array(
                'placeholder'=>$this->settings->server_email
            )
        ));
        
        $this->add_input('success_message', array(
            'type'=>'text',
            'label'=>'Сообщение об отправке',
            'help'=>'Это сообщение будет показано пользователю при успешной отправке. Если не указано, то будет показано стандартное сообщение'
        ));
        
        $this->add_input('inputs', new Input(array(
            'tab'=>'inputs'
        )));
    }
}