<?php

namespace Email\Forms;

/**
 * Форма для шаблона писем
 */
class Email extends \Admin\Form
{
    public $default_from;
    
    function save()
    {
        $this->load->library('Tix\Email');
        
        $group = $this->post('to');
        
        $this->db->start_cache();
            if( $group != 'all' )
            {
                $this->users_m->where('group_alias', $group);
            }
            else
            {
                $this->users_m->where('id !=', 0);
            }
        $this->db->stop_cache();

        $from = $this->from();
        $subject = $this->post('subject');
        $message = $this->post('message');
        
        $step = 1;
        $limit = 100;        
        $users = TRUE;
        $now = time();
        $total = 0;
        while($users)
        {
            $offset = ($step - 1)*$limit;
            $users = $this->users_m->limit($limit)->offset($offset)->get_all();
            $step++;
            
            if( $users )
            {
                $emails = array();
                foreach($users as $user)
                {
                    if( $user->email )
                    {
                        $emails[] = array(
                            'from'=>$from,
                            'to'=>$user->email,
                            'subject'=>$subject,
                            'message'=>$message,
                            'type'=>'text',
                            'created_on'=>$now,
                            'priority'=>$this->email->priority
                        );
                        
                        $total++;
                    }
                }
                
                $this->email->add_to_queue_batch($emails);
            }
        }
        
        $this->db->flush_cache();
        
        $this->total_emails = $total;    
        
        return TRUE;
    }
    
    function from()
    {
        return $this->post('from') ? $this->post('from') : $this->default_from;
    }
    
    function init()
    {
        parent::init();

        $this->default_from = $this->settings->server_email;
        
        $this->inputs = array(
            'from'=>array(
                'type'=>'text',
                'label'=>'От кого',
                'rules'=>'trim|valid_email',
                'placeholder'=>$this->default_from
            ),
            'to'=>array(
                'type'=>'select',
                'label'=>'Кому',
                'rules'=>'trim|required',
                'options'=>$this->get_group_options()
            ),
            'subject'=>array(
                'type'=>'text',
                'label'=>'Тема',
                'rules'=>'trim|required',
                'placeholder'=>''
            ),
            'message'=>array(
                'type'=>'textarea',
                'label'=>'Сообщение',
                'rules'=>'trim|required',
                'attrs'=>array(
                    'style'=>'width: 100%;'
                )
            )
        );

        $this->actions['submit']['value'] = 'Отправить';

        if( $this->module->url == 'email' )
        {
            unset($this->actions['back_url']);
        }

        unset($this->actions['submit-more']);
        unset($this->actions['submit-stay']);
    }
    
    function get_group_options()
    {
        $this->load->model('users/groups_m');
        
        $this->groups_m->where('alias != ', 'guests');
        $groups = $this->groups_m->get_all();
        
        return array_merge(
            array('all'=>'Всем'),
            \Helpers\CArray::map($groups, 'alias', 'name')
        );
    }
}