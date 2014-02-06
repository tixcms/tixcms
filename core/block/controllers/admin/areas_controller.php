<?php

class Areas_Controller extends Block\Controllers\Backend
{    
    /**
     * Добавление областей для ссылок
     */
    function form($item)
    {
        $form = $this->load->library('Block\Forms\Area', array(
            'entity'=>$item,
            'model'=>$this->block_areas_m,
            'block_id'
        ));

        if( $form->submitted() )
        {
            if( $form->save() )
            {
                // сообщение
                $message = $item ? 'Изменения сохранены' : 'Область ссылок добавлена';
                
                if( $this->is_ajax() )
                {
                    echo json_encode(array(
                        'type'=>'success',
                        'text'=>$message
                    ));
                    
                    return;
                }
                else
                {
                    $this->alert_flash('success', 'Область ссылок добавлена');
                    
                    // редирект
                    $this->redirect('admin/block');
                }
            }
            else
            {
                if( $this->is_ajax() )
                {
                    echo json_encode(array(
                        'type'=>'error',
                        'text'=>$form->get_errors()
                    ));
                    
                    return;
                }
                else
                {
                    $this->alert('error', $form->get_errors());
                }
            }
        }
        
        $this->render('areas/form', array(
            'item'=>$item,
            'form'=>$form
        ));
    }
    
    /**
     * Добавление
     */
    function action_add()
    {        
        $this->form(FALSE);
    }
    
    /**
     * Редактирование
     */
    function action_edit($id)
    {
        $item = $this->block_areas_m->by_id($id)->get_one();
        
        $this->form($item);
    }
    
    /**
     * Удаление области
     * 
     */
    function action_delete($id)
    {
        $area = $this->block_areas_m->by_id($id)->get_one();
        
        // удаляем область
        $this->block_areas_m->by_id($id)->delete();
        
        // удаляем ссылки
        $this->block_m->by_area_alias($area->alias)->delete();
        
        $message = 'Область блоков удалена';
        
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