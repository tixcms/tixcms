<?php

class Categories
{
    protected $action;
    
    function __construct($action)
    {
        $this->action = $action;
        
        // модель
        $this->load->model('categories/categories_m');
        
        $this->di->assets->css('categories::style.css');
        
        $this->template->add_layout('categories::layout');
        
        if( !$this->base_category_exists() )
        {
            $this->create_base_category();
        }
    }
    
    private function base_category_exists()
    {
        return $this->categories_m->by_level(0)->by_module($this->module->url)->count();
    }
    
    private function create_base_category()
    {
        $this->categories_m
                    ->set_module($this->module->url)
                    ->set_title('Корневая')
                    ->create_base_category();
    }
    
    function index()
    {
        $table = $this->load->library('Categories\Table', array(
            'model'=>$this->categories_m,
            'per_page'=>false,
            'where'=>array('module'=>$this->module->url, 'level >'=>\Categories_m::TOP_LEVEL),
            'search'=>false
        ));
             
        // скрипты   
        $this->di->assets->js('jquery::plugins/jquery.validate.js');
        $this->di->assets->js('jquery::plugins/jquery.validate/local.ru.js');
        $this->di->assets->js('categories::script.js');
        
        $this->template->render('categories::index', array(
            'table'=>$table
        ));
    }
    
    function add()
    {
        $this->form(FALSE);
    }
    
    function edit()
    {
        $id = $this->action == 'add' ? FALSE : $this->uri->segment(5);
        
        $category = $this->categories_m->by_id($id)->get_one();
        
        $this->form($category);
    }
    
    /**
     * Удаление категории и вложенных подкатегорий
     */
    function delete($category_id)
    {
        if( $this->input->is_ajax_request() )
        {
            $this->db->start_cache();
            $this->categories_m->where('module', $this->module->url);
            $this->db->stop_cache();
            
                $deleted_categories = $this->categories_m->delete($category_id);
            
            $this->db->flush_cache();
            
            $this->di->events->trigger($this->module->url .'.category.delete', array(
                'deleted_categories'=>$deleted_categories
            )); 
            
            echo json_encode(array(
                'type'=>'success',
                'text'=>'Категория удалена'
            ));   
        }
        else
        {
            show_404();
        }
    }
    
    function form($category)
    {
        $form = $this->load->library('Categories\Form', array(
            'entity'=>$category, 
            'model'=>$this->categories_m
        ));
        
        if( $form->submitted() )
        {
            if( $form->save() )
            {
                if( $this->input->is_ajax_request() )
                {
                    echo json_encode(array(
                        'type'=>'success',
                        'text'=>'Изменения сохранены'
                    ));
                    
                    return;
                }
                else
                {
                    $this->di->alert->set_flash('success', 'Изменения сохранены');
                    
                    if( $this->input->post('submit-more') OR $this->input->post('apply') )
                    {
                        \URL::referer();
                    }
                    else
                    {
                        \URL::redirect('admin/'. $this->module->url .'/categories');
                    }
                }                
            }
            else
            {
                $this->di->alert->set('error', $form->get_errors());
            }
        }
        
        $this->template->render('categories::form', array(
            'category'=>$category,
            'form'=>$form
        ));
    }
    
    function reorder()
    {
        if( $this->input->is_ajax_request() )
        {
            $items = $this->input->post('ids');
            
            if( is_array($items) AND $items )
            {
                foreach($items as $item)
                {
                    $this->categories_m->by_id($item['item_id'])
                        ->set_level($item['depth'])
                        ->set_lft($item['left'])
                        ->set_rgt($item['right'])
                        ->update();
                }
            }
            
            echo json_encode(array(
                'type'=>'success',
                'text'=>'Порядок страниц изменен'
            ));
            
            return;
        }
        
        $categories = \CI::$APP->categories_m
                                    ->by_module($this->module->url)
                                    ->order_by('lft', 'ASC')
                                    ->where('level >', Categories_m::TOP_LEVEL)
                                    ->get_all();
       
        $this->di->assets->js('jquery::ui/sortable.min.js');
        $this->di->assets->js('jquery::ui/nestedSortable.js');
        $this->di->assets->js('categories::reorder.js');
        
        $this->di->assets->css('categories::reorder.css');
        
        $this->template->render('categories::reorder', array(
            'categories'=>$categories
        ));
    }
    
    /**
     * Изменение активности категории
     */
    function active($id)
    {
        $category = $this->categories_m->by_id($id)->get_one();
        
        $this->categories_m
                    ->by_id($id)
                    ->set_is_active(!$category->is_active)
                    ->update();
        
        if( $this->input->is_ajax_request() )
        {
            echo json_encode(array(
                'type'=>'success',
                'text'=>'Статус изменен'
            ));
        }
        else
        {
            show_404();
        }
    }
    
    function recount($model_path = false, $model_alias = false, $cat_field = false, $filter = false)
    {
        if( !$model_path OR !$model_alias OR !$cat_field )
        {
            echo 'Нужно указать $model_path, $model_alias, $cat_field';
            
            exit();
        }
        
        \Categories\Helper::items_recount($this->module->url, str_replace('.', '/', $model_path), $model_alias, $cat_field, $filter);

        \CI::$APP->di->alert->set_flash('success', 'Элементы пересчитаны');

        \URL::redirect('admin/'. $this->module->url);
    }
    
    function display()
    {
        $valid_actions = array(
            'reorder', 'edit', 'index', 'add', 'delete', 'active', 'recount'
        );
        
        if( !in_array($this->action, $valid_actions) )
        {
            show_404();
        }
        
        $remove_segments_count = 4;
        
        $params = array_splice($this->uri->segment_array(), $remove_segments_count);
        
        return call_user_func_array(array($this, $this->action), $params);
    }
    
    function __get($name)
    {
        return CI::$APP->$name;
    }
}