<?php

class Nav_Controller extends Nav\Controllers\Backend
{
    /**
     * Вывод областей и ссылок
     */
    function action_index($id = false)
    {
        // области ссылок
        $areas = $this->nav_areas_m->get_all();
        
        // ссылки
        $links = $this->nav_m->order_by('parent_id ASC, order ASC')->get_all();
        $links = $links ? $this->reqursive($links) : false;
        $links = $this->group_links_by_area_alias($links);

        // форма добавления ссылки
        $link = $this->nav_m->by_id($id)->get_one();

        $this->render('index', array(
            'areas'=>$areas,
            'links'=>$links,
        ));
    }
    
    function action_add()
    {
        if( !$this->nav_areas_m->count() )
        {
            $this->alert_flash('attention', 'Чтобы добавить ссылку, создайте область ссылок');
            
            $this->referer();
        }
        
        $this->form();
    }
    
    function action_edit($id)
    {
        if( !$link = $this->nav_m->by_id($id)->get_one() )
        {
            show_404();
        }
        
        $this->form($link);
    }

    public function reqursive($navs, $parent = 0, $level = 0)
    {
        static $data = array();

        foreach($navs as $nav)
        {
            $nav = $nav;

            if( $nav->parent_id == $parent )
            {
                $nav->level = $level;
                $data[] = $nav;

                $this->reqursive($navs, $nav->id, $level + 1);
            }
        }

        return $data;
    }
    
    /**
     * Добавление и редактирование ссылки
     */
    function form($link = false)
    {
        // форма
        $form = $this->load->library('Nav\Forms\Link', array(
            'entity'=>$link,
            'model'=>$this->nav_m
        ));

        // обрабатываем данные
        if( $form->submitted() )
        {
            // валидация и сохранение
            if( $form->save() )
            {                
                $form->response('success', array(
                    'add'=>'Ссылка добавлена',
                    'edit'=>'Изменения сохранены'
                ));
            }
            // валидация не пройдена
            else
            {   
                $form->response('error');
            }
        }
        
        if( $this->is_ajax() )
        {
            echo $form->render();
            
            return;
        }
        
        $this->render('form', array(
            'form'=>$form
        ));
    }

    /**
     * Изменение статуса ссылки
     */
    function action_status($id)
    {
        if( $this->is_ajax() )
        {
            $nav = $this->nav_m->by_id($id)->get_one();

            $this->nav_m->by_id($id)->set_status(!$nav->status)->update();
        
            $message = 'Статус изменен';

            echo json_encode(array(
                'type'=>'success',
                'text'=>$message
            ));
        }
        else
        {
            show_404();
        }
    }

    /**
     * Удаление ссылки
     */
    function action_delete($id)
    {
        if( $this->is_ajax() )
        {
            $this->nav_m->by_id($id)->delete();
        
            $message = 'Ссылка удалена';

            echo json_encode(array(
                'type'=>'success',
                'text'=>$message
            ));
        }
        else
        {
            show_404();
        }
    }
    
    /**
     * Ajax изменение порядка ссылок
     */
    function action_position()
    {
        $link_id = $this->input->post('link_id');
        $parent_id = $this->input->post('parent_id');
        $ids = $this->input->post('ids');
        
        $this->nav_m->by_id($link_id)->set('parent_id', $parent_id)->update();
        
        if( $ids )
        {
            $i=1;
            foreach($ids as $id)
            {
                $this->nav_m->by_id($id)->set('order', $i)->update();
                $i++;
            }
        }
        
        echo json_encode(array(
            'type'=>'success',
            'text'=>'Изменения сохранены'
        ));
    }
    
    /**
     * Группирует ссылки по областям
     */
    function group_links_by_area_alias($links)
    {
        $result = array();
        if( $links )
        {
            foreach($links as $link)
            {
                $result[$link->area_alias][$link->parent_id][] = $link;
            }
        }
        
        return $result;
    }
}