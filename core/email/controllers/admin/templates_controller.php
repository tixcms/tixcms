<?php

class Templates_Controller extends Email\Controller
{
    function __construct()
    {
        parent::__construct();
        
        // модель
        $this->load->model('email/templates_m');
        
        // хлебные крошки
        $this->crumb('Шаблоны писем', 'admin/email/templates');
    }
    
    /**
     * Вывод списка шаблонов
     */
    function action_index()
    {
        $templates = \Email\Templates::get_list();
        
        $this->di->assets->js('jquery::plugins/jquery.form.js');
        $this->di->assets->js('templates.js');
        
        $this->render('email::templates/index', array(
            'templates'=>$templates
        ));
    }
    
    /**
     * Создание и редактирование шаблона
     */
    function action_form($module = false, $alias = false)
    {
        if( !$entity = $this->templates_m->get($module, $alias) )
        {
            show_404();
        }
        
        $form = $this->load->library('Email\Forms\Template', array(
            'entity'=>$entity,
            'model'=>$this->templates_m
        ));
        
        echo $form->render();
    }
    
    function action_save($module = false, $alias = false)
    {
        if( !$entity = $this->templates_m->get($module, $alias) )
        {
            show_404();
        }
        
        $form = $this->load->library('Email\Forms\Template', array(
            'entity'=>$entity,
            'model'=>$this->templates_m
        ));
        
        if( $form->submitted() )
        {
            if( $form->save() )
            {
                echo json_encode(array(
                    'type'=>'success',
                    'text'=>'Изменения сохранены'
                ));
            }
            else
            {
                echo json_encode(array(
                    'type'=>'error',
                    'text'=>$form->get_errors()
                ));
            }
        }
    }
}