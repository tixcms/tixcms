<?php

namespace Email\Forms;

/**
 * Форма для шаблона писем
 */
class Template extends \Admin\Form
{
    public $view = 'email::templates/_form';
    public $csrf_protection = false;
    public $alias;
    
    function init()
    {
        parent::init();
        
        $this->attrs['action'] = \URL::site_url('admin/email/templates/save/'. $this->entity->module .'/'. $this->entity->alias);
        
        $this->inputs = array(
            'from'=>array(
                'type'=>'text',
                'label'=>'Письмо отправителя',
                'rules'=>'trim|valid_email',
                'help'=>'Указанная здесь почта будет указана в качестве отправителя. 
                Если оставить пустым, то будет использован адрес почты указанный в настройка'
            ),
            'subject'=>array(
                'type'=>'text',
                'label'=>'Тема письма',
                'rules'=>'trim|required',
                'value'=>$this->entity->subject
            ),
            'text'=>array(
                'type'=>'textarea',
                'view'=>'email::templates/_text',
                'label'=>'Содержание',
                'rules'=>'trim|required',
                'help'=>'Текст письма',
                'attrs'=>array(
                    'style'=>'width: 100%;'
                ),
                'xss'=>false,
                'vars'=>$this->entity->vars
            )
        );
        
        if( !isset($this->entity->from) OR !$this->entity->from )
        {
            $this->inputs['from']['placeholder'] = $this->settings->server_email;
        }
        
        $this->actions = array(
            \URL::anchor('admin/email/templates', lang('back'), array('class'=>'btn')),
            '<input type="submit" name="submit" value="'. lang('save') .'" class="btn btn-primary" />',
            $this->is_update() 
                ? '<input type="submit" name="apply" value="'. lang('apply') .'" class="btn btn-primary ajax-submit" />'
                : '',
        );
    }
    
    function update()
    {
        $this->model
                ->by_module($this->entity->module)
                ->by_alias($this->entity->alias)
                ->set($this->post_data)
                ->update();
    }
    
    function insert()
    {
        $this->model
                ->set_module($this->entity->module)
                ->set_alias($this->entity->alias)
                ->insert($this->post_data);
    }
    
    function is_update()
    {
        return $this->model->by_module($this->entity->module)->by_alias($this->entity->alias)->count();
    }
    
    function is_insert()
    {
        return !$this->model->by_module($this->entity->module)->by_alias($this->entity->alias)->count();
    }
    
    function render_actions()
    {
        return;
    }
}