<?php

namespace Security;

use CI;

class Events 
{
    function __construct()
    {
        $class = $this;
        
        CI::$APP->di->events->register('admin.login_page_enter', function(){
            $session_data = CI::$APP->session->all_userdata();
            CI::$APP->load->model('security/logs_m');
            if( CI::$APP->settings->security_attempts_limit_count > 0 AND CI::$APP->input->post() )
            {
                $attempts = CI::$APP->logs_m
                    ->by_ip($session_data['ip_address'])
                    ->where('created_on > ', time() - CI::$APP->settings->security_attempts_block_time*60)
                    ->count();

                if( $attempts > CI::$APP->settings->security_attempts_limit_count )
                {
                    if( CI::$APP->settings->security_attempls_violation_notice )
                    {
                        $email = CI::$APP->settings->security_attempls_violation_notice_email
                            ? CI::$APP->settings->security_attempls_violation_notice_email
                            : CI::$APP->settings->server_email;

                        CI::$APP->load->library('Tix\Email');
                        CI::$APP->email->to($email);
                        CI::$APP->email->from(CI::$APP->settings->server_email);
                        CI::$APP->email->subject('Уведомление безопасности');
                        CI::$APP->email->message('Было обнаружено превышение разрешенного количества
                        попыток входа в панель управления с ip '. $session_data['ip_address']);

                        CI::$APP->email->send();
                    }

                    CI::$APP->di->alert->set_flash(
                        'error',
                        'Вы превысели количество разрешенных попыток на ввод пароля.
                        Попробуйте через '. CI::$APP->settings->security_attempts_block_time .' минут(у)'
                    );

                    \URL::referer();
                }
            }
        });
        
        CI::$APP->di->events->register('admin.login_success', function($data)use($class){
            $class->log($data, 'login');
        });
        
        CI::$APP->di->events->register('admin.login_fail', function($data)use($class){
            $class->log($data, 'login-fail');
        });
        
        CI::$APP->di->events->register('users.login_success', function($data) use($class){            
            $class->log($data, 'login');
        });
        
        CI::$APP->di->events->register('users.login_fail', function($data) use($class){            
            $class->log($data, 'login-fail');
        });
        
        CI::$APP->di->events->register('users.logout', function($data) use($class){            
            $class->log($data, 'logout');
        });
    }
    
    public function log($data, $type)
    {
        CI::$APP->load->model('security/logs_m');
        
        $types = array(
            'logout'=>\Logs_m::TYPE_LOGOUT, 
            'login'=>\Logs_m::TYPE_LOGIN, 
            'login-fail'=>\Logs_m::TYPE_FAIL_LOGIN
        );
        
        $session_data = CI::$APP->session->all_userdata();
        $insert_data = array(
            'user_id'=>isset($data['user_id']) ? $data['user_id'] : 0,
            'login'=>isset($data['login']) ? $data['login'] : '',
            'created_on'=>time(),
            'ip'=>$session_data['ip_address'],
            'user_agent'=>$session_data['user_agent'],
            'type'=>$types[$type],
            'backend'=>(isset($data['backend']) AND $data['backend'])
        );

        CI::$APP->logs_m->insert($insert_data);
    }
}