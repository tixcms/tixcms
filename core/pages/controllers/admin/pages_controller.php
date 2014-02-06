<?php

class Pages_Controller extends Pages\Controllers\Backend 
{
    public $has_help = TRUE;
    
    function __construct()
    {
        parent::__construct();
        
        // модель
        $this->load->model('pages_m');
    }

    /**
     * Список страниц
     */
    function action_index()
    {
        $items = $this->pages_m->order_by('lft', 'ASC')->where('level !=', 0)->get_all();
        
        $table = Admin\Table::create(array(
            'headings'=>array(
                'Название',
                'Ссылка',
                'Главная',
                'Активна',
                ''
            ),
            'items'=>$items,
            'item_view'=>'_item',
            'no_items'=>'Нет страниц',
            'search'=>false
        ));

        $this->render(array(
            'table'=>$table
        ));
    }
    
    function action_set_main($id = false)
    {
        if( !Security::check_csrf_token() )
        {
            show_404();
        }
        
        if( !$page = $this->pages_m->by_id($id)->get_one() )
        {
            show_404();
        }
        
        if( $page->is_main )
        {
            show_404();
        }
        
        $this->pages_m->set_is_main(0)->update();
        $this->pages_m->by_id($id)->set_is_main(1)->update();
        
        if( $this->is_ajax() )
        {
            echo json_encode(array(
                'type'=>'success',
                'text'=>'Изменения сохранены'
            ));
        }
        else
        {
            $this->alert_flash('success', 'Изменения сохранены');
            
            $this->referer();
        }
    }

    /**
     * Изменение активности страницы
     */
    function action_active($id)
    {
        if( !Security::check_csrf_token() )
        {
            show_404();
        }
        
        $page = $this->pages_m->by_id($id)->get_one();

        $this->pages_m
            ->by_id($id)
            ->set_is_active(!$page->is_active)
            ->update();
        
        $this->di->events->trigger('pages.admin.active.changed');
        
        if( $this->is_ajax() )
        {
            echo json_encode(array(
                'type'=>'success',
                'text'=>'Статус изменен'
            ));
        }
        else
        {
            $this->alert_flash('success', 'Статус изменен');
            
            $this->referer();
        }
    }
    
    /**
     * Сотрировка страниц
     */
    function action_reorder()
    {
        if( $this->is_ajax() )
        {
            $items = $this->input->post('ids');
            
            if( is_array($items) AND $items )
            {
                foreach($items as $item)
                {
                    $this->pages_m->by_id($item['item_id'])
                        ->set_level($item['depth'])
                        ->set_lft($item['left'])
                        ->set_rgt($item['right'])
                        ->update();
                }
            }
            
            $this->pages_m->check_url_consistency();
            
            echo json_encode(array(
                'type'=>'success',
                'text'=>'Порядок страниц изменен'
            ));
            
            return;
        }
        
        $items = $this->pages_m->order_by('lft', 'ASC')->where('level !=', 0)->get_all();
        
        if( !$items )
        {
            $this->alert_flash('attention', 'Не создано ни одной страницы');
            
            $this->redirect('admin/pages');
        }  
        
        $this->di->assets->js('jquery::ui/sortable.min.js');
        $this->di->assets->js('jquery::ui/nestedSortable.js');
        $this->di->assets->js('reorder.js');
        
        $this->di->assets->css('reorder.css');
        
        $this->render('reorder', array(
            'items'=>$items
        ));
    }
    
    function action_add()
    {
        $this->form(FALSE);
    }
    
    function action_edit($id)
    {
        $item = $this->pages_m->by_id($id)->get_one();
        
        $this->form($item);
    }
    
    function form($item)
    {
        $form = $this->load->library('Pages\Form', array(
            'entity'=>$item, 
            'model'=>$this->pages_m
        ));
        
        if( $form->submitted() )
        {
            if( $form->save() )
            {
                $this->events->trigger($form->is_update() ? 'pages.admin.edit' : 'pages.admin.add');
                
                $form->response('success', array(
                    'add'=>'Страница добавлена',
                    'edit'=>'Изменения сохранены'
                ));
            }
            else
            {
                $form->response('error');
            }
        }
        
        $this->render('form', array(
            'form'=>$form
        ));
    }
    
    /**
     * Удаление страницы
     */
    function action_delete($id)
    {
        if( !Security::check_csrf_token() )
        {
            show_404();
        }
        
        // удаляем страницу
        $page = $this->pages_m->by_id($id)->get_one();
        
        if( !$page OR $page->level == 0 )
        {
            show_404();
        }
        
        $this->pages_m->delete($id);
        
        if( $page->is_main )
        {            
            $this->pages_m->by_is_active(1)->set_is_main(1)->where('level !=', 0)->limit(1)->update();
        }
        
        $this->events->trigger('pages.admin.delete');
        
        if( $this->is_ajax() )
        {
            echo json_encode(array(
                'type'=>'success',
                'text'=>'Страница удалена'
            ));
        }
        else
        {
            $this->alert_flash('success', 'Страница удалена');
            
            $this->referer();
        }
    }
}