<?php

namespace Modules\Addons;

class Entity
{
    /**
     * Имя модуля
     */
    public $name;

    /**
     * Автор
     */
    public $author = '';
    
    /**
     * Описание модуля
     */
    public $description = '';   
    
    /**
     * Ссылка
     */
    public $url; 
    
    /**
     * Версия
     */
    public $version = '';
    
    /**
     * Показывать в меню
     */
    public $is_menu = 0;
    
    /**
     * Работает ли в публичной части сайта
     */
    public $is_frontend = 0;
    
    /**
     * Дополнение как сервис, например, комментарии или оценки
     */
    public $is_service = 0;
    
    /**
     * Ядро
     */
    public $is_core = 0;
    
    /**
     * Группа
     */
    public $group = 'no_group';
    
    /**
     * Работает ли в административной части сайта
     */
    public $is_backend = 0;
    
    function install(){}
    
    function uninstall(){}
    
    final function default_install()
    {
        $this->add_to_modules();
        $this->add_to_settings();
    }
    
    final function default_uninstall()
    {
        // удаляем из модулей
        $this->remove_from_modules();
        
        // удаляем из настроек
        $this->db->where('module', $this->url)->delete('settings');
        
        $this->uninstall();
    }
    
    final function update($current_version, $new_version)
    {
        $this->update_settings();
        
        $versions = $this->versions();
        
        if( empty($versions) )
        {
            return;
        }
        
        foreach($versions as $version)
        {
            if( \Helpers\String::versions_compare($version, $current_version) == 1 
                AND \Helpers\String::versions_compare($version, $new_version) <= 0 )
            {
                $method = 'update_to_'. str_replace('.', '_', $version);
                
                if( method_exists($this, $method) )
                {
                    $this->$method();
                }
            }
        }
    }
    
    protected function versions()
    {
        return array();
    }
    
    protected function files()
    {
        return array();
    }
    
    public function requires()
    {
        return array();
    }
    
    protected function add_to_modules()
    {
        $data = array(
            'name'=>is_array($this->name) ? json_encode($this->name) : $this->name,
            'author'=>$this->is_core ? 'TixCMS' : $this->author,
            'desc'=>is_array($this->description) ? json_encode($this->description) : $this->description,
            'url'=>$this->url,
            'is_frontend'=>$this->is_frontend,
            'is_backend'=>$this->is_backend,
            'is_service'=>$this->is_service,
            'is_menu'=>$this->is_menu,
            'is_core'=>$this->is_core,
            'version'=>$this->version,
            'group_alias'=>$this->group
        );
        
        $this->db->insert('modules', $data);
    }
    
    protected function add_to_settings()
    {
        if( $settings = \Settings\Helper::get_settings_class($this->url) )
        {
            foreach($settings->inputs as $input)
            {
                if( !$settings->is_input_personal($input) )
                {
                    $this->db->insert('settings', array(
                        'id'=>$input->field,
                        'module'=>$this->url,
                        'value'=>isset($input->default) ? $input->default : ''
                    ));
                }
            }
        }
    }
    
    private function update_settings()
    {
        if( $settings = \Settings\Helper::get_settings_class($this->url, array('module'=>$this->url)) )
        {
            $this->db->where('module', $this->url);
            $result = $this->db->get('settings');
            
            $old_settings = array();
            if( $result->num_rows() > 0 )
            {
                // старые настройки
                foreach($result->result() as $item)
                {
                    $old_settings[$item->id] = $item;
                }
            }
                
            // проверяем не появились ли новые настройки
            foreach($settings->inputs as $input)
            {
                if( !array_key_exists($input->field, $old_settings) )
                {
                    if( !$settings->is_input_personal($input) )
                    {
                        $this->db->insert('settings', array(
                            'id'=>$input->field,
                            'module'=>$this->url,
                            'value'=>isset($input->default) ? $input->default : ''
                        ));
                    }
                }

                if( !$settings->is_input_personal($input) )
                {
                    unset($old_settings[$input->field]);
                }
            }
            
            // удаляем старые настройки
            if( count($old_settings) )
            {
                foreach($old_settings as $item)
                {
                    $this->db->where('module', $this->url)->where('id', $item->id)->delete('settings');
                }
            }
        }
    }
    
    private function remove_from_modules()
    {
        $this->db->where('url', $this->url)->delete('modules');
    }
    
    function add_nav_areas($data = array())
    {
        if( $data )
        {
            $this->load->model('nav/nav_areas_m');
            
            foreach($data as $nav)
            {
                if( $this->nav_areas_m->by_alias($nav['alias'])->count() == 0 )
                {
                    $this->nav_areas_m->insert($nav);
                }
            }
        }
    }
    
    /**
     * Добавление областей блоков
     */
    function add_block_areas($data = array())
    {
        if( $data )
        {
            $this->load->model('block/block_areas_m');
            
            foreach($data as $block)
            {
                if( $this->block_areas_m->by_alias($block['alias'])->count() == 0 )
                {
                    $this->block_areas_m->insert($block);
                }
            }
        }
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}