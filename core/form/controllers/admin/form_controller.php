<?php

class Form_Controller extends \Admin\Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('form/forms_m');
    }
    
    public function action_index()
    {
        $list = $this->load->library('Form\Table', array(
            'model'=>$this->forms_m
        ));        
        
        $this->render(array(
            'list'=>$list
        ));
    }
    
    public function form($item)
    {
        $form = $this->load->library('Form\Generated\Add', array(
            'entity'=>$item,
            'model'=>$this->forms_m
        ));
        
        if( $form->submitted() )
        {
            if( $form->save() )
            {
                $form->response('success', array(
                    'add'=>'Форма добавлена',
                    'edit'=>'Изменения сохранены'
                ));
            }
            else
            {
                $form->response('error');
            }
        }
        
        $this->assets->js('jquery::ui/sortable.min.js');
        
        $this->render('form', array(
            'form'=>$form
        ));
    }
    
    public function action_add()
    {
        $this->form(false);
    }
    
    public function action_edit($id)
    {
        if( !$form = $this->forms_m->by_id($id)->get_one() )
        {
            show_404();
        }
        
        $this->form($form);
    }
    
    function action_get_type($type, $i)
    {        
        $input = new \stdClass;
        
        $input->type = $type;
        $input->label = '';
        $input->rules = '';
        
        echo \Form\Generated\Input::render_input('', $input, $i);
    }
    
    public function action_delete($id)
    {
        if( !Security::check_csrf_token() )
        {
            show_404();
        }
        
        $this->forms_m->by_id($id)->delete();
        
        if( $this->is_ajax() )
        {
            echo json_encode(array(
                'type'=>'success',
                'text'=>'Форма удалена'
            ));
        }
        else
        {
            $this->alert_flash('success', 'Форма удалена');
            
            $this->referer();
        }
    }
}