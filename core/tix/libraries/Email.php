<?php

namespace Tix;

use CI;

class Email extends \CI_Email {
    
    private $template = false;  // шаблон письма
    private $data = array();    // данные для шаблона письма

    /**
     *  Функция нужна, чтобы текст оставался в оригинальном
     *  виде, а изначальная функция добавляет разные служебные символы
     */
    function subject($subject)
	{       
	    $this->_set_header('RawSubject', $subject);

		$subject = $this->_prep_q_encoding($subject);
		$this->_set_header('Subject', $subject);
        return $this;
	}
    
    /**
     * Отправка письма
     */
    function send()
    {
        $this->_safe_mode = TRUE;
        
        return parent::send();
    }
    
    /**
     * Добавление письма в очередь на отправку
     *
     */
    function add_to_queue($emails = array())
    {
        $CI =& get_instance();

        $data = array(
            'from'=>$this->_headers['From'],
            'to'=>$this->_recipients,
            'subject'=>$this->_headers['RawSubject'],
            'message'=>$this->_body,
            'type'=>$this->mailtype,
            'created_on'=>time(),
            'priority'=>$this->priority
        );

        $CI->db->insert('email_queue', $data);
    }
    
    function add_to_queue_batch($emails)
    {
        $CI =& get_instance();
        
        $CI->db->insert_batch('email_queue', $emails);
    }

    /**
     * Отправление писем из очереди
     *
     */
    function send_queue($count = 10)
    {
        $CI =& get_instance();

        $CI->db->limit($count);
        $CI->db->order_by('priority DESC, created_on ASC');
        $emails = $CI->db->get('email_queue');

        if( $emails->num_rows() > 0 )
        {
            $ids = array();
            foreach($emails->result() as $email)
            {
                $this->from($email->from);
                $this->to($email->to);
                $this->subject($email->subject);
                $this->message($email->message);
                $this->set_mailtype($email->type);
                
                if( !$this->send() )
                {
                    log_message('error', $this->print_debugger());
                }
                
                $ids[] = $email->id;
            }

            $CI->db->where_in('id', $ids);
            $CI->db->delete('email_queue');
        }
    }
}