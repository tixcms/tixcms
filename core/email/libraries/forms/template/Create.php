<?php

namespace Email\Forms\Template;

/**
 * Форма для шаблона писем
 */
class Create extends \Admin\Form
{    
    function init()
    {
        // переменные
        //$this->load->model('email/template_vars_m');
        //$this->vars = $this->template_vars_m->by_template_alias($this->entity->alias)->get_all();
        
        $this->attrs = array(
            //'action'=>\URL::site_url('admin/email/templates/save/'. $this->entity->alias)
        );
        
        $this->load->model('modules/modules_m');
        
        $this->inputs = array(
            'alias'=>array(
                'type'=>'text',
                'label'=>'Идентификатор',
                'rules'=>'trim|required|alpha_dash'
            ),
            'module'=>array(
                'type'=>'select',
                'label'=>'Модуль',
                'options'=>\CArray::map($this->modules_m->order_by('title')->get_all(), 'url', 'title')
            ),
            'name'=>array(
                'type'=>'text',
                'label'=>'Название',
                'rules'=>'trim|required'
            ),
            'description'=>array(
                'type'=>'textarea',
                'label'=>'Описание',
                'rules'=>'trim'
            ),
            'from'=>array(
                'type'=>'text',
                'label'=>'От кого',
                'rules'=>'trim|valid_email'
            ),
            'subject'=>array(
                'type'=>'text',
                'label'=>'Тема письма',
                'rules'=>'trim|required',
                'placeholder'=>''
            ),
            'text'=>array(
                'type'=>'textarea',
                'label'=>'Содержание',
                'rules'=>'trim|required',
                'help'=>'Текст письма',
                'attrs'=>array(
                    'style'=>'width: 100%;'
                )
            )
        );
        
        if( !$this->is_update() )
        {
            $this->inputs['from']['placeholder'] = $this->settings->server_email;
        }
        
        $this->actions = array(
            \URL::anchor('admin/email/templates', 'Вернуться', array('class'=>'btn')),
            '<input type="submit" name="submit" value="Сохранить" class="btn btn-primary" />',
            $this->is_update() 
                ? '<input type="submit" name="apply" value="Применить" class="btn btn-primary ajax-submit" />'
                : '',
        );
    }
}