<?php

namespace Nav\Forms;

class Link extends \Admin\Form
{
    public $ajax = true;
    
    function before_insert()
    {
        $link = $this->nav_m->by_area_alias($this->get('area_alias'))->limit(1)->order_by('order', 'DESC')->get_one();
        $order = $link ? $link->order + 1 : 0;
        $this->set('order', $order + 1);
    }
    
    function init()
    {
        parent::init();

        $areas = $this->get_areas();
        
        $this->inputs = array(
            'name'=>array(
                'type'=>'text',
                'label'=>'Название',
                'rules'=>'trim|required|max_length[50]',
                'help'=>'Название ссылки'
            ),
            'type'=>array(
                'type'=>'custom',
                'view'=>'nav::inputs/type',
                'label'=>'Тип ссылки'
            ),
            'url'=>array(
                'type'=>'custom',
                'view'=>'nav::inputs/url',
                'label'=>'Ссылка',
                'rules'=>'trim',
                'data'=>$this->get_data(),
                'url_type'=>$this->is_update() ? $this->entity->type : false
            ),
            'area_alias'=>array(
                'label'=>'Область ссылок',
                'type'=>'select',
                'options'=>$areas,
                'rules'=>'trim|required'
            ),
            'parent_id'=>array(
                'view'=>'inputs/parent_id',
                'type'=>'select',
                'label'=>'Вложенность',
                'options'=>$this->get_parent_options()
            ),
            'access'=>$this->load->library('Admin\Form\Input\Access'),
            'status'=>new \Form\Input\Checkbox(array(
                'label'=>'Показывать на сайте'
            )),
            'new_window'=>$this->load->library('Form\Input\Checkbox', array(
                'label'=>'Открывать в новом окне',
                'default_value'=>false
            ))
        );
    }

    /**
     * Построение дерева навигации
     *
     */
    public function reqursive($navs, $parent, $level)
    {
        static $data = array();
        
        if( $navs )
        {
            foreach($navs as $nav)
            {
                $nav = (array)$nav;
    
                if( $nav['parent_id'] == $parent )
                {
                    $nav['level'] = $level;
                    $nav['parent_id'] = $nav['parent_id'];
                    $data[] = $nav;
    
                    $this->reqursive($navs, $nav['id'], $level + 1);
                }
            }
        }

        return $data;
    }

    function get_parent_options()
    {
        if( $this->is_update() )
        {
            $this->nav_m->where('id !=', $this->entity->id);
        }
        
        $items = $this->nav_m->order_by('order ASC')->get_all();

        $items = $this->reqursive($items, 0, 0);

        $options = array();
        if( $items )
        {
            foreach($items as $item)
            {
                $options[$item['area_alias']][] = array(
                    'value'=>$item['id'],
                    'label'=>str_repeat('&nbsp;&nbsp;&nbsp;', $item['level']) . $item['name'],
                    'area_alias'=>$item['area_alias']
                );
            }
        }
        
        return $options;
    }
    
    /**
     * Области для вывода в выпадающем списке
     */
    function get_areas()
    {
        $areas_options = array();
        
        if( $areas = $this->nav_areas_m->get_all() )
        {
            foreach($areas as $area)
            {
                $areas_options[$area->alias] = $area->name;
            }
        }
        
        return $areas_options;
    }
    
    /**
     * Модули и страницы для вывода в выпадающем списке
     */
    function get_data()
    {
        $this->load->model('pages/pages_m');
        
        // страницы
        $pages = $this->pages_m->order_by('lft', 'ASC')->where('level > ', 0)->get_all();
        
        return array(
            'pages'=>$pages
        );
    }
}