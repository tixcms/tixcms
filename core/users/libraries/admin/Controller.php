<?php

namespace Users\Admin;

class Controller extends \Admin\Controller
{
    public $has_settings = true;
    public $has_help = true;
    public $has_emails = true;
    
    function __construct()
    {
        parent::__construct();
        
        // хлебные крошки
        $this->crumb('Пользователи', 'admin/users');
        
        // вложенный шаблон
        $this->template->add_layout('nav-layout');
    }
    
    function action_settings($action = 'index')
    {
        $this->template->remove_layout();
        
        return parent::action_settings($action);
    }
    
    function action_emails($action = 'index')
    {
        $this->template->remove_layout();
        
        return parent::action_emails($action);
    }
    
    /**
     * Дополнительные вкладки из других модулей
     */
    function tabs($user_id)
    {
        $tabs = array();
        foreach(\Modules\Helper::get() as $module)
        {
            $module_class = $module->url .'\Users\Profile';
            
            if( class_exists($module_class) )
            {
                $tabs[] = array(
                    'label'=>$module->name,
                    'active'=>$this->uri->segment(6) == $module->url,
                    'url'=>'admin/users/profile/view/'. $user_id .'/'. $module->url
                );
            }
        }
        
        return $tabs;
    }
}