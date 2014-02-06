<?php

namespace Form;

class Generated extends \App\Form
{
    public $item;
    public $item_inputs;
    protected $csrf_protection = false;
    
    public function init()
    {
        parent::init();
        
        $this->item_inputs = (array)json_decode($this->item->inputs);
        $this->inputs = $this->get_inputs($this->item_inputs);
        
        $this->attrs['action'] = $this->di->url->site_url('form/process/'. $this->item->alias);
    }
    
    public function get_inputs($inputs)
    {        
        foreach($inputs as $key=>$input)
        {
            if( $input->type == 'email' )
            {
                $inputs[$key]->type = 'text';
            }
            
            $inputs[$key]->rules = 'trim';
            
            if( isset($input->required) AND $input->required )
            {
                $inputs[$key]->rules .= '|required';
            }
            
            if( isset($input->valid_email) AND $input->valid_email )
            {
                $inputs[$key]->rules .= '|valid_email';
            }
        }
        
        return $inputs;
    }
    
    public function save()
    {
        $message = '';
        foreach($this->item_inputs as $key=>$input)
        {
            $message .= $input->label .': '. $this->get($key) ."\n";
        }

        $this->di->email->message($message);
        $this->di->email->from($this->settings->server_email, $this->settings->site_name);
        $this->di->email->to($this->item->email ? $this->item->email : $this->settings->server_email);
        $this->di->email->subject('Сообщение отправлено через форму '. $this->item->name);
        
        $this->di->email->send();
        
        return true;
    }
    
    static function view($alias, $view = false)
    {
        \CI::$APP->load->model('form/forms_m');
        
        $item = \CI::$APP->forms_m->by_alias($alias)->get_one();
        
        if( !$item )
        {
            return false;
        }
        
        $data['item'] = $item;
        
        if( $view )
        {
            $data['view'] = $view;
        }
        
        $form = new \Form\Generated($data);
        
        $form->form_errors = \CI::$APP->session->userdata('form_errors');
        $form->form_data = \CI::$APP->session->userdata('form_data');
        
        \CI::$APP->session->unset_userdata('form_errors');
        \CI::$APP->session->unset_userdata('form_data');
        
        return $form->render();
    }
}