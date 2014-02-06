<?php

namespace Categories;

class Form extends \Admin\Form
{
    const IMG_UPLOAD_PATH = 'uploads/categories/';
    
    public $inputs_folder = 'admin::input/';
    
    function insert()
    {
        $this->set('module', $this->module->url);
        
        $this->db->start_cache();
            $this->model->where('module', $this->module->url);
        $this->db->stop_cache();
        
            $this->model->insert($this->post_data, $this->post('parent_id'));
        
        $this->db->flush_cache();
    }
    
    function init()
    {
        parent::init();
        
        $this->inputs = array(
            'title'=>array(
                'type'=>'text',
                'label'=>'Название',
                'rules'=>'trim|required',
                'attrs'=>array(
                    'class'=>'required'
                )
            ),
            'url'=>new \Form\Input\Url(array(
                'label'=>'Ссылка',
                'source_input'=>'title',
                'url_prepend'=>false
            )),
           'description'=>array(
                'type'=>'textarea',
                'label'=>'Описание',
                'rules'=>'trim'
            ),
            'parent_id'=>array(
                'type'=>'select',
                'label'=>'Подкатегория',
                'options'=>$this->get_categories_options(),
                'value'=>$this->is_insert() ? 0 : $this->entity->id,
                'rules'=>'trim',
                'attrs'=>array(
                    'class'=>'required'
                ),
                'visibility'=>$this->is_insert(),
                'save'=>FALSE
            ),
            'is_active'=>new \Form\Input\Checkbox(array(
                'label'=>'Активна'
            )),
            'icon'=>new \Form\Input\File\Image\Simple(array(
                'config'=>array(
                    'upload_path'=>self::IMG_UPLOAD_PATH
                ),
                'required'=>false
            )),
            'meta'=>new \Form\Input\Meta(array(
                'placeholders_fields'=>array(
                    'title'=>'title',
                    'description'=>'description',
                    'keywords'=>''
                )
            ))
        );
        
        $this->actions['back_url']['href'] = 'admin/'. $this->module->url .'/categories';
    }
    
    function get_categories_options()
    {
        $module = $this->is_insert() ? $this->module->url : $this->entity->module;
        
        $categories = $module 
                            ? $this->categories_m
                                            ->by_module($module)
                                            ->order_by('lft', 'ASC')
                                            ->get_all()
                            : FALSE;
        
        //$cat_options[0] = 'Корневая категория';
        if( $categories )
        {
            foreach($categories as $cat)
            {
                $cat_options[$cat->id] = str_repeat('&nbsp;&nbsp;', $cat->level).$cat->title;
            }
        }
        
        return $cat_options;
    }
}