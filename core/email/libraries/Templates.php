<?php

namespace Email;

class Templates
{
    /**
     * Вывод списка шаблонов
     */
    static function render()
    {
        $ci = \CI::$APP;

        $ci->di->assets->js('jquery::plugins/jquery.form.js');
        $ci->di->assets->js('email::templates.js');
        
        $ci->load->model('email/templates_m');
        
        $templates = $ci->templates_m->get_by_module($ci->module->url);
        
        $table = \Admin\Table::create(array(
            'headings'=>array(
              'Название',
              'Описание'
            ),
            'item_view'=>'email::templates/_item',
            'items'=>$templates,
            'no_items'=>'<p>Нет шаблонов</p>',
            'search'=>false,
            'per_page'=>false,
            'show_total_counter'=>false
          ));
        
        $ci->template->render('email::templates/index', array(
            'table'=>$table
        ));
    }
    
    static function send($module, $template_name, $data)
    {
        \CI::$APP->load->model('email/templates_m');
        
        $template = \CI::$APP->templates_m->get($module, $template_name);
        
        if( !$template )
        {
            return false;
        }
        
        // загружаем класс
        \CI::$APP->load->library('Tix\Email', array(), 'email');
        
        // сообщение
        $message = \CI::$APP->template->parse($template->text, $data);        
        $from = isset($template->from) ? $template->from : \CI::$APP->settings->server_email;
        
        \CI::$APP->email->message($message);
        \CI::$APP->email->from($from, \CI::$APP->settings->site_name);
        \CI::$APP->email->to($data['email']);
        \CI::$APP->email->subject($template->subject);
        \CI::$APP->email->send();
    }
    
    /**
     * Возвращает список доступных блоков
     * 
     * @param array
     */    
    static function get_list()
    {
        $modules = array_merge(glob('core/*'), glob('addons/*'), glob('themes/*'));
        
        $modules_installed = \Modules\Helper::get();
        
        $templates = array();
        foreach($modules as $module)
        {
            if( array_key_exists($module, $modules_installed) )
            {
                continue;
            }
            
            $pos = strpos($module, '/');
            $module = substr($module, $pos + 1);
            
            $email_class = ucfirst($module) . '\Emails';
            
            if( class_exists($email_class) )
            {
                $emails = \CI::$APP->load->library($email_class);

                $templates_data = $emails->data();
                
                foreach( $templates_data as $alias=>$template )
                {
                    $templates[$module][$alias] = $template;
                    $templates[$module][$alias]['module'] = $module;
                }
            }
        }
        
        return $templates;
    }
}