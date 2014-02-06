<?php

namespace Pages;

class Form extends \Admin\Form
{
    public $ajax = true;
    public $show_search = false;
    
    function tabs()
    {
        return array(
            'main'=>'Текст',
            'more'=>'Опции'
        );
    }

    function init()
    {
        parent::init();
        
        $module_options = $this->get_modules_options();
        
        $this->inputs = array(
            'module'=>array(
                'type'=>'select',
                'label'=>'Прикрепленный модуль',
                'options'=>$this->get_modules_options(),
                'visible'=>$module_options AND ($this->is_insert() OR $this->entity->level < 2),
                'help'=>'Вы можете прикрепить к данной странице дополнение из списка.<br />Дополнения могут быть прикреплены только к страницам первого уровня'
            ),
            'title'=>array(
                'type'=>'text',
                'label'=>'Название',
                'rules'=>'trim|required',
                'help'=>'Название вашей страницы.'
            ),
            'url'=>$this->load->library('Form\Input\Url', array(
                'label'=>'Ссылка на страницу',
                'source_input'=>'title',
                'url_prepend'=>'',
                'help'=>'Определяет адрес, по которому будет доступна страница.<br />Оставьте поле пустым для автоматической генерации.',
                //'save'=>($this->is_insert() OR $this->entity->level != 0),
                /*'attrs'=>array(
                    'disabled'=>($this->is_insert() OR $this->entity->level != 0 OR !$this->entity->is_main)
                            ? ''
                            : 'disabled'
                ),*/
                'attrs'=>array(
                    ($this->is_update() AND $this->entity->is_main) ? 'disabled' : ''=>'disabled'
                ),
                // убираем урл, если страница главная
                ($this->is_update() AND $this->entity->is_main) ? 'value' : 'qwert'=>''
            )),
            'parent_id'=>array(
                'type'=>'select',
                'label'=>'Вложенность',
                'options'=>$this->get_pages(),
                'value'=>$this->is_insert() ? 0 : $this->entity->id,
                'rules'=>'trim',
                'attrs'=>array(
                    'class'=>'required'
                ),
                'visible'=>$this->is_insert(),
                'save'=>false
            ),
            'body'=>array(
                'label'=>'Текст',
                'type'=>'textarea',
                'wysiwyg'=>true,
                'xss'=>false
            ),
            
            'view'=>array(
                'type'=>'text',
                'label'=>'Шаблон',
                'tab'=>'more'
            ),
            'is_main'=>$this->load->library('Form\Input\Checkbox', array(
    			'label'=>'Главная',
                'default_value'=>false,
                'tab'=>'more'
    		)),
            'is_active'=>$this->load->library('Form\Input\Checkbox', array(
    			'label'=>'Показывать на сайте',
                'visible'=>($this->is_insert() OR $this->entity->level != 0),
                'tab'=>'more'
    		)),
            'access'=>$this->load->library('Admin\Form\Input\Access', array(
                'tab'=>'more',
                'label'=>lang('Доступ к странице')
            )),
            'meta'=>$this->load->library('Form\Input\Meta', array(
                'tab'=>'more'
            ))
        );
    }
    
    function get_modules_options()
    {
        $modules = $this->modules_m->by_is_frontend(1)->get_all();
        
        $options = array('Не прикреплен');
        
        if( $modules )
        {
            $modules_options = \Helpers\CArray::map($modules, 'url', 'name');
            
            return $options + $modules_options;
        }
        else
        {
            return false;
        }       
    }
    
    function insert()
    {
        $this->insert_id = $this->model->insert(array(
            'module'=>$this->get('module'),
            'title'=>$this->get('title'),
            'body'=>$this->get('body'),
            'is_active'=>$this->get('is_active'),
            'is_main'=>$this->get('is_main'),
            'url'=>$this->get('url'),
            'pre_url'=>$this->get('pre_url'),
            'meta_title'=>$this->get('meta_title'),
            'meta_keywords'=>$this->get('meta_keywords'),
            'meta_description'=>$this->get('meta_description'),
            'access'=>$this->get('access')
        ), $this->post('parent_id'));
    }
    
    function before_insert()
    {
        $this->set('pre_url', $this->generate_pre_url($this->post('parent_id')));
    }
    
    function before_save()
    {
        // title
        $this->set('title', htmlspecialchars($this->get('title')));
        
        $this->set_module();
    }
    
    function after_save()
    {
        if( $this->was_set_new_main_page() )
        {
            $this->set_other_pages_not_main();
        }
    }
    
    function was_set_new_main_page()
    {
        return ($this->is_update() AND !$this->entity->is_main AND $this->get('is_main')) 
            OR ($this->is_insert() AND $this->get('is_main') != '');
    }
    
    function set_other_pages_not_main()
    {
        $page_id = $this->is_update() ? $this->entity->id : $this->insert_id;
        
        $this->model->where('id !=', $page_id)->set_is_main(0)->update();
    }
    
    /**
     * Прикрепление модуля к странице
     * 
     * Модуль может быть прикреплен только к странице первого уровня,
     * на страницах с уровнем вложенности больше единицы,
     * прикрепление не будет работать
     */
    function set_module()
    {
        // прикрепление к модулю
        if( ($this->is_insert() AND $this->post('parent_id') == 1) OR ($this->is_update() AND $this->entity->level < 2 ) )
        {
            $this->set('module', $this->get('module') ? $this->get('module') : '' );
        }
        else
        {
            $this->set('module', '');
        }
    }
    
    function before_update()
    {
        // Изменения preurl
        if( $this->entity->url != $this->get('url') )
        {
            $childs = $this->pages_m->order_by('lft', 'ASC')->get_childs($this->entity);

            if( $childs )
            {
                $pre_url[] = $this->get('url');
                $level = $this->entity->level;

                foreach($childs as $item)
                {
                    if( $item->level <= $level )
                    {
                        $times = $level - $item->level + 1;
                        
                        for($i=$times; $i>0; $i--)
                        {
                            array_pop($pre_url);
                        }                        
                    }
                    
                    $level = $item->level;

                    $this->pages_m->by_id($item->id)->update(array(
                        'pre_url'=>implode('/', $pre_url)
                    ));                    
                    
                    $pre_url[] = $item->url;
                }
            }
        }
    }
    
    function get_pages()
    {
        $items = $this->pages_m->order_by('lft', 'ASC')->where('module', '')->where('level >', 0)->get_all();
        
        $options = array(1=>'Корневая');
        if( $items )
        {
            foreach($items as $item)
            {
                $options[$item->id] = str_repeat('&nbsp;&nbsp;', $item->level) . $item->title;
            }
        }
        
        return $options;
    }
    
    function generate_pre_url($id)
    {
        $preurl = array();
        $parent = $this->pages_m->by_id($id)->get_one();
        
        if( $parent->level > 0 )
        {
            $parents = $this->pages_m->where('level !=', 0)->order_by('lft', 'ASC')->get_parents($parent);
            
            if( $parents )
            {
                foreach($parents as $item)
                {
                    $preurl[] = $item->url;
                }
            }
            
            $preurl[] = $parent->url;
        }
        
        return implode('/', $preurl);
    }
}