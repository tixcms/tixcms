<?php

class Settings extends \Settings\Form
{
    /**
     * Модули
     */
    public $modules;
    
    public $errors = array();
    
    const LOGO_UPLOAD_PATH = 'uploads/images/';
    
    function validate()
    {
        $validated = true;
        foreach($this->modules as $module)
        {
            if( $module->form->validate() === FALSE )
            {
                $this->errors = array_merge($module->form->get_errors('array'), $this->errors);
                $validated = FALSE;
            }
        }
        
        if( parent::validate() == FALSE )
        {
            $this->errors = array_merge($this->get_errors('array'), $this->errors);
            $validated = FALSE;
        }
        
        return $validated;
    }
    
    function save()
    {
        foreach($this->modules as $module)
        {
            $module->form->save();
        }
        
        parent::save();
    }
    
    function get_errors($type = '')
    {
        if( $type == 'array' )
        {
            return $this->errors;
        }
        else
        {
            parent::get_errors($type);
        }
    }
    
    function init()
    {
        parent::init();
        
        $this->all();
    }
    
    function get_images_src()
    {
        $this->images = parent::get_images_src();
        foreach($this->modules as $module)
        {
            $this->images = array_merge($this->images, $module->form->get_images_src());
        }
        
        return $this->images;
    }
    
    function inputs()
    {
        return array(
            'frontend_enabled'=>array(
                'type'=>'select',
                'label'=>'Состояние сайта',
                'options'=>array(
                    1=>'Открыт',
                    0=>'Закрыт'
                ),
                'tab'=>'general',
                'default'=>1
            ),
            'unavailable_message'=>array(
                'type'=>'textarea',
                'label'=>'Сообщение при закрытом сайте',
                'tab'=>'general',
                'default'=>'Сайт не работает'
            ),
            'server_email'=>array(
                'type'=>'text',
                'label'=>'Почта',
                'help'=>'Почта будет использоваться в качестве основной на сайте',
                'rules'=>'trim|valid_email',
                'code'=>TRUE,
                'tab'=>'general',
                'default'=>'email@example.com'
            ),
            'site_name'=>array(
                'type'=>'text',
                'label'=>'Заголовок сайта',
                'help'=>'Текст, который будет выводиться в заголовке сайта',
                'default'=>'TixCMS',
                'tab'=>'seo'
            ),
            'site_description'=>array(
                'type'=>'text',
                'label'=>'Описание сайта',
                'help'=>'Текст, который будет добавлен в тег description',
                'default'=>'',
                'tab'=>'seo'
            ),
            'site_keywords'=>array(
                'type'=>'text',
                'label'=>'Ключевые слова',
                'help'=>'Слова будут добавлены в тег keywords',
                'default'=>'',
                'tab'=>'seo'
            )
        );
    }
    
    function all()
    {
        $modules_with_settings = array();
        foreach(\Modules\Helper::get() as $module)
        {
            if( $module->url != 'settings' AND ( !isset($this->user) OR $this->user->module_access($module->url) ) )
            {
                $settings_class = \Settings\Helper::get_settings_class($module->url, array(
                    'model'=>$this->model,
                    'module'=>$module->url
                ));
                
                if( $settings_class )
                {
                    $module->form = $settings_class;
                    $modules_with_settings[] = $module;
                }
            }
        }
        
        $this->modules = $modules_with_settings;
    }
}