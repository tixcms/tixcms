<?php

class Areas_Controller extends Nav\Controllers\Backend
{    
    /**
     * Создание и редактирование областей ссылок
     */
    function form($area = false)
    {
        $form = $this->load->library('Nav\Forms\Area', array(
            'entity'=>$area,
            'model'=>$this->nav_areas_m
        ));

        // обрабатываем данные
        if( $form->submitted() )
        {
            // валидация и сохранение
            if( $form->save() )
            {
                $form->response('success', array(
                    'add'=>'Область ссылок добавлена',
                    'edit'=>'Изменения сохранены'
                ), '', 'admin/nav/areas/edit/{id}');               
            }
            // валидация не прошла
            else
            {
                $form->response('error');
            }
        }
        
        $this->render('areas/form', array(
            'item'=>$area,
            'form'=>$form
        ));
    }
    
    /**
     * Добавление областей для ссылок
     */
    function action_add()
    {
        $this->form();
    }
    
    /**
     * Редактирование области ссылок
     */
    function action_edit($id)
    {
        $area = $this->nav_areas_m->by_id($id)->get_one();
        
        $this->form($area);
    }
    
    /**
     * Удаление области
     * 
     */
    function action_delete($id)
    {
        if( !Security::check_csrf_token() )
        {
            show_404();
        }
        
        $area = $this->nav_areas_m->by_id($id)->get_one();
        
        // удаляем область
        $this->nav_areas_m->by_id($id)->delete();
        
        $this->nav_m->by_area_alias($area->alias)->delete();

        $message = 'Область ссылок удалена';

        if( $this->is_ajax() )
        {
            echo json_encode(array(
                'type'=>'success',
                'text'=>$message
            ));
        }
        else
        {
            $this->alert_flash('success', $message);
            
            $this->referer();
        }
    }
}