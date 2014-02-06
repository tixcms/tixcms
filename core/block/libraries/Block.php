<?php

abstract class Block 
{
    protected $block;
    protected $data = array();
    protected $options = array();
    protected $view;
    protected $need_render = true;
    protected $block_module;
    static private $blocks_by_areas = null;
    
    static function view($block, $options = array())
    {
        return self::get($block, $options);
    }
    
    static function get($block, $options = array())
    {        
        list($module, $block) = explode('::', $block);
        
        $class = $module .'\Blocks\\'. $block;
        
        $options = is_string($options) ? unserialize($options) : $options;
        
        $block = \CI::$APP->load->library($class, array('module'=>$module, 'path'=>$block));

        return $block->run($options);
    }
    
    function __construct($params)
    {
        $this->block_module = strtolower($params['module']);
        $this->block = str_replace('\\', DIRECTORY_SEPARATOR, strtolower($params['path']));
    }
    
    protected function run($options)
    {        
        if( $options )
        {
            foreach($options as $key=>$value)
            {
                $this->options[$key] = $value;
            }
        }
        
        $this->view = ( isset($this->options['view'])
                            ? $this->options['view']
                            : ( $this->view
                                    ? $this->view
                                    : $this->block_module .'::blocks/'. $this->block
                              ) 
                      );
            
        $this->data = $this->data();
        
        if( !$this->need_render )
        {
            return;
        }
        else
        {
            return $this->render();
        }
    }
    
    abstract function data();
    
    protected function render()
    {
        return $this->template->view(
            $this->view,
            is_array($this->data) ? array_merge($this->data, $this->options) : $this->options
        );
    }
    
    /**
     * Вывод уже созданного блока
     */
    static function inst($id, $template = 'block::templates/default')
    {
        $ci = \CI::$APP;
        
        $ci->load->model('block/block_m');

        $block = $ci->block_m->by_id($id)->get_one();    
        
        return self::get($block->block_class, unserialize($block->data));
    }
    
    static function area($alias, $area_template = 'block::templates/area', $block_template = 'block::templates/block')
    {
        $ci = \CI::$APP;
        
        if( self::$blocks_by_areas === null )
        {
            $ci->load->model('block/block_m');
            
            $blocks = $ci->block_m->by_active(1)->order_by('order', 'ASC')->get_all();
            
            if( $blocks )
            {
                foreach($blocks as $block)
                {
                    if( $block->access() )
                    {
                        self::$blocks_by_areas[$block->area_alias][] = $block;
                    }
                }
            }
            else
            {
                self::$blocks_by_areas = false;
            }
        }
        
        if( isset(self::$blocks_by_areas[$alias]) )
        {
            $blocks_view = array();
            
            foreach(self::$blocks_by_areas[$alias] as $block)
            {
                if( $block_template === false )
                {
                    $blocks_view[] = self::get($block->block_class, unserialize($block->data));
                }
                else
                {
                    $blocks_view[] = $ci->template->view($block_template, array(
                        'block'=>$block,
                    ));
                }
            }
            
            if( $area_template === false )
            {
                return implode('', $blocks_view);
            }
            else
            {
                return $ci->template->view($area_template, array(
                    'blocks'=>$blocks_view
                ));
            }
        }
        else
        {
            return false;
        }
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
        
        $blocks = array();
        foreach($modules as $module)
        {
            if( !is_dir($module) )
            {
                continue;
            }
            
            $pos = strpos($module, '/');
            $module = substr($module, $pos + 1);
            
            $block_class = ucfirst($module) . '\Blocks';
            
            if( class_exists($block_class) )
            {
                $block = \CI::$APP->load->library($block_class);
                
                if( is_subclass_of($block, 'Block\Items') )
                {
                    $blocks_data = $block->items();
                    
                    foreach( $blocks_data as $key=>$data )
                    {
                        if( !isset($modules_installed[$module]) )
                        {               
                            if( !isset($data['visible_if_module_not_installed']) OR !$data['visible_if_module_not_installed'] )
                            {
                                continue;
                            }
                        }
                        
                        $blocks[] = array_merge(
                            array(
                                'module'=>$module,
                                'alias'=>$key
                            ),
                            $data
                        );
                    }
                }
            }
        }
        
        return $blocks;
    }
    
    /**
     * Возвращает блок
     * 
     * @param string Модуль
     * @param string Алиас
     * @return mixed
     */
    static function get_item($module, $alias)
    {
        if( !$module OR !$alias )
        {
            return false;
        }
        
        $block_class = ucfirst($module) . '\Blocks';
        
        if( !class_exists($block_class) )
        {
            return false;
        }
        
        $block = \CI::$APP->load->library($block_class);
        
        $blocks_data = $block->items();
        
        if( !isset($blocks_data[$alias]) )
        {
            return false;
        }
        
        $return_block = $blocks_data[$alias];
        $return_block['module'] = $module;
        $return_block['alias'] = $alias;
        
        return $return_block;
    }
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}