<?php

class Form_Controller extends \App\Controller
{
    function action_process($alias = '')
    {
        $this->load->model('form/forms_m');
        
        if( !$item = $this->forms_m->by_alias($alias)->get_one() )
        {
            show_404();
        }
        
        $form = $this->load->library('Form\Generated', array(
            'item'=>$item
        ));
        
        if( $form->submitted() )
        {
            if( $form->validate() )
            {
                $form->save();
                
                $message = $item->success_message ? $item->success_message : 'Сообщение успешно отправлено';
                
                $this->alert->set_flash('success', $message);
            }
            else
            {
                $form_errors = array();
                $form_data = array();
                foreach($form->inputs as $name=>$input)
                {
                    $form_errors[$name] = $form->error($name);
                    $form_data[$name] = $form->post($name);
                }
                
                $this->session->set_userdata('form_errors', $form_errors);
                $this->session->set_userdata('form_data', $form_data);
                $this->alert->set_flash('error', $form->get_errors());
            }
            
            $this->referer();
        }
        else
        {
            show_404();
        }
    }
}