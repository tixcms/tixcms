<?php

namespace Admin;

class Form extends \Form
{
    public $inputs_folder = 'admin::input/';
    public $view = 'admin::form';
    public $ajax = false;
    public $attrs = array(
        'class'=>'form-horizontal'
    );
    public $show_help = true;
    public $tabs;
    public $settings_by_tabs = array();
    public $actions_view = 'admin::form/actions';
    public $response_data = array();
    
    /**
     * Нужно ли загружать wysiwyg
     */
    public $load_wysiwyg = false;

    protected $success_insert_message = 'Операция выполнена успешно';
    protected $success_update_message = 'Изменения сохранены';

    function init()
    {
        parent::init();
        
        $this->load->helper('language');
        
        $this->tabs = $this->tabs();
        $this->actions = $this->set_default_actions();
    }
    
    /**
     * Tinymce и CKEditor не работают нормально с аяксом, поэтому отключаем
     */
    function need_to_turn_ajax_off()
    {
        if( $this->load_wysiwyg AND ( class_exists('Tinymce') OR class_exists('CKEditor') ) )
        {
            $wysiwyg = $this->config->item('wysiwyg_instance') 
                ? get_class($this->config->item('wysiwyg_instance'))
                : false;
            
            return $wysiwyg == 'Tinymce' OR $wysiwyg == 'CKEditor';
        }
        else
        {
            return false;
        }
    }
    
    function wysiwyg()
    {
        foreach($this->inputs as $name=>$input)
        {
            if( isset($input->wysiwyg) AND $input->wysiwyg )
            {
                $this->inputs[$name]->attrs['class'] = 'wysiwyg';
                
                $this->load_wysiwyg = true;
                
                $this->config->set_item('wysiwyg_active', true);
            }
        }
    }

    function set_default_actions()
    {
        return array(
            'back_url'=>array(
                'href'=>'admin/'. ( is_object(\CI::$APP->module) ? \CI::$APP->module->url : \CI::$APP->module),
                'label'=>lang('back'),
                'attrs'=>array('class'=>'btn')
            ),
            'submit'=>array(
                'attrs'=>array('class'=>"btn btn-primary ajax-submit"),
                'value'=>'Сохранить и выйти'
            ),
            'apply'=>array(
                'attrs'=>array('class'=>"btn btn-primary ajax-submit"),
                'value'=>'Сохранить и остаться',
                'visible'=>$this->is_update()
            ),
            'submit-more'=>array(
                'attrs'=>array('class'=>"btn btn-primary ajax-submit"),
                'value'=>'Сохранить и добавить еще',
                'visible'=>$this->is_insert()
            ),
            'submit-stay'=>array(
                'attrs'=>array('class'=>"btn btn-primary ajax-submit"),
                'value'=>'Сохранить и остаться',
                'visible'=>$this->is_insert()
            ),
            'ajax-img-loader'=>array(
                'src'=>'admin::ajax-loader.gif'
            )
        );
    }

    function set_response_data($data = array())
    {
        foreach($data as $key=>$value)
        {
            $this->response_data[$key] = $value;
        }
    }
    
    /**
     * Стандатрные действия после завершения обработки формы
     * 
     * @param enum success|error Тип ответа
     */
    function response($type, $messages = array(), $back_url = false, $edit_url = false)
    {
        if( $this->is_insert() )
        {
            $messages['add'] = isset($messages['add']) ? $messages['add'] : $this->response_message['text'];
        }
        else
        {
            $messages['edit'] = isset($messages['edit']) ? $messages['edit'] : $this->response_message['text'];
        }

        if( $type == 'success' )
        {
            $back_url = $back_url ? $back_url : 'admin/'. $this->module->url;            
            if( $this->is_insert() )
            {
                if( !$edit_url )
                {
                    $edit_url = 'admin/'. $this->module->url .'/edit/' . $this->insert_id;
                }
                else
                {
                    $edit_url = str_replace('{id}', $this->insert_id, $edit_url);
                }
            }
            
            if( $this->input->is_ajax_request() )
            {
                $action = '';
                $url = '';
                
                if( $this->input->post('submit') OR $this->input->post('submit-more') )
                {
                    $this->di->alert->set_flash('success', $this->is_update() ? $messages['edit'] : $messages['add']);

                    $action = 'redirect';
                    $url = $this->input->post('submit') ? $back_url : $this->uri->uri_string;
                }
                elseif( $this->input->post('submit-stay') OR $this->input->post('apply') )
                {
                    if( $this->input->post('apply') )
                    {
                        $action = 'stay';
                    }
                    else
                    {
                        $this->di->alert->set_flash($this->response_message['type'], $this->is_update() ? $messages['edit'] : $messages['add']);

                        $action = 'redirect';
                        $url = $edit_url;
                    }
                }
                
                echo json_encode(array(
                    'type'=>$this->response_message['type'],
                    'text'=>$this->is_update() ? $messages['edit'] : $messages['add'],
                    'onSuccess'=>array(
                        'action'=>$action,
                        'url'=>$url,
                        'isInsert'=>$this->is_insert()
                    ),
                    'data'=>$this->response_data,
                    'images'=>$this->get_images_src()
                ));
                
                exit;
            }
            else
            {
                $this->di->alert->set_flash($this->response_message['type'], $this->is_update() ? $messages['edit'] : $messages['add']);

                if( $this->input->post('submit') OR $this->input->post('submit-more') )
                {
                    $this->input->post('submit') 
                        ? \URL::redirect($back_url) 
                        : \URL::referer();
                }
                elseif( $this->input->post('submit-stay') OR $this->input->post('apply') )
                {
                    if( $this->input->post('apply') )
                    {
                        \URL::referer();
                    }
                    else
                    {
                        \URL::redirect($edit_url);
                    }
                }
            }     
        }
        else
        {
            if( $this->input->is_ajax_request() )
            {
                echo json_encode(array(
                    'type'=>$this->response_message['type'],
                    'text'=>$this->get_errors(),
                    'errors'=>$this->get_errors('array'),
                    'data'=>$this->response_data,
                ));
                
                exit;
            }
            else
            {
                $this->di->alert->set($this->response_message['type'], $this->get_errors());
            }
        }
    }

    function render_attrs()
    {
        if( $this->ajax )
        {
            $this->attrs['class'] = $this->attrs['class'] .' ajax';
        }
        
        return parent::render_attrs();
    }
    
    function render_label($field)
    {
        return $this->template->view($this->inputs_folder . 'label', array(
            'label'=>$this->inputs[$field]->label,
            'help'=>''
        ));
    }
    
    function before_render()
    {
        $this->actions_position = $this->user->settings('admin_form_actions_position');

        parent::before_render();
        
        if( $this->tabs )
        {
            $this->reorder_by_tabs();
        }
        
        $this->wysiwyg();
        
        if( $this->need_to_turn_ajax_off() )
        {
            $this->ajax = false;
        }
        
        if( $this->ajax )
        {
            $this->di->assets->js('jquery::plugins/jquery.form.js');
            $this->di->assets->js('admin::form.js');
            $this->inline_errors = true;
        }
    }
    
    private function reorder_by_tabs()
    {        
        $default_tab = false;
        $tabs = $this->tabs;
        foreach($tabs as $key=>$name)
        {
            if( !$default_tab )
            {
                $default_tab = $key;
            }
            
            $this->tabs[$key] = $name;
            $this->settings_by_tabs[$key] = array();
        }
        
        foreach($this->inputs as $key=>$input)
        {
            if( isset($input->tab) )
            {
                $this->settings_by_tabs[$input->tab][] = $key;
            }
            else
            {
                $this->settings_by_tabs[$default_tab][] = $key;
            }
        }
    }
    
    function tabs()
    {
        return false;
    }
    
    function get_images_src()
    {
        $this->images = array();
        foreach($this->inputs as $name=>$input)
        {
            if( is_a($input, 'Form\Input\File\Image') AND isset($input->data) )
            {
                $this->images[] = array(
                    'field'=>$input->field,
                    'src'=>\URL::site_url($input->config['upload_path'] . $input->data['file_name'])
                );
            }
        }
        
        return $this->images;
    }

    function set_response_message()
    {
        if( $this->validated_successfully )
        {
            $this->response_message['type'] = 'success';
            $this->response_message['text'] = $this->is_insert() ? $this->success_insert_message : $this->success_update_message;
        }
        else
        {
            $this->response_message['type'] = 'error';
            $this->response_message['text'] = $this->get_errors();
        }
    }
}