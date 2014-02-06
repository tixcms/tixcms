<?php

class Email_Controller extends Email\Controller
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('sent_m');
    }
    
    function action_index()
    {
        $this->redirect('admin/email/compose');
    }
    
    /**
     * Выводит форму и отправляет письма
     */
    function action_compose()
    {
        $form = new Email\Forms\Email;
        
        if( $form->submitted() )
        {
            if( $form->validate() )
            {
                $form->save();
                
                $this->sent_m->insert(array(
                    'to'=>$form->post('to'),
                    'from'=>$form->from(),
                    'subject'=>$form->post('subject'),
                    'message'=>$form->post('message'),
                    'created_on'=>time(),
                    'user_id'=>$this->user->id,
                    'count'=>$form->total_emails
                ));
                
                $this->alert_flash('success', 'Сообщение отправлено');
                
                $this->referer();
            }
            else
            {
                $this->alert('error', $form->get_errors());
            }
        }
        
        $this->render(array(
            'form'=>$form
        ));
    }
    
    function action_sent()
    {
        $items = $this->sent_m
                            ->select($this->sent_m->table.'.*, u.login, g.name AS group_name')
                            ->join('users AS u', 'u.id = '. $this->sent_m->table .'.user_id')
                            ->join('users_groups AS g', $this->sent_m->table.'.to = g.alias', 'left')
                            ->order_by('created_on', 'DESC')
                            ->get_all();
        
        $this->render(array(
            'items'=>$items
        ));
    }
}