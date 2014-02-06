<?php

class Block_Controller extends Block\Controllers\Backend
{    
    /**
     * Вывод областей и блоков
     */
    function action_index()
    {
        $areas = $this->block_areas_m->get_all();
        $blocks = $this->block_m->order_by('order', 'ASC')->get_all();
        
        $result = array();
        if( $blocks )
        {
            foreach($blocks as $block)
            {
                $result[$block->area_alias][] = $block;
            }
        }
        
        $list = new Admin\TList(array(
            'view'=>'admin::tlist',
            'items'=>$areas,
            'item_view'=>'areas/_item',
            'no_items'=>'<p>Не создано областей для блоков</p>',
            'per_page'=>false
        ));

        $this->render(array(
            'areas'=>$areas,
            'blocks'=>$result,
            'list'=>$list
        ));
    }
    
    /**
     * Включение отключение блока
     */
    function action_active($id)
    {
        if( $this->is_ajax() )
        {
            $block = $this->block_m->by_id($id)->get_one();

            $this->block_m->by_id($id)->set_active(!$block->active)->update();

            echo json_encode(array(
                'type'=>'success',
                'text'=>'Блок '. (!$block->active ? 'включен' : 'выключен')
            ));
        }
        else
        {
            show_404();
        }
    }
    
    /**
     * Удаление блока
     */
    function action_delete($id)
    {
        if( $this->is_ajax() )
        {
            $this->block_m->by_id($id)->delete();
        
            echo json_encode(array(
                'type'=>'success',
                'text'=>'Блок удален'
            ));
        }
        else
        {
            show_404();
        }
    }
    
    /**
     * Редактирование блока
     */
    function action_edit($block_id = false)
    {
        if( !$block = $this->block_m->by_id($block_id)->get_one() )
        {
            show_404();
        }
        
        $this->form($block->block_module, $block->block_alias, $block);
    }
    
    /**
     * Добавление блока
     */
    function action_add($module = false, $block_alias = false)
    {
        if( !$this->block_areas_m->count() )
        {
            $this->alert_flash('attention', 'Создайте область блоков');
            
            $this->referer();
        }
        
        $this->form($module, $block_alias);
    }
    
    /**
     * Добавление и редактирование блока
     */
    function form($module = false, $block_alias = false, $block_inst = false)
    {        
        // все блоки
        $blocks = \Block::get_list();
        
        $blocks_list = Block\TList::create(array(
            'item_view'=>'_item',
            'items'=>$blocks
        ));
        
        $block = \Block::get_item($module, $block_alias);

        $form = false;        
        if( $block )
        {
            $this->load->model('block_m');
            
            $form_class = str_replace('::', '\Blocks\Forms\\', $block['class']);
            
            $form = $this->load->library($form_class, array(
                'entity'=>$block_inst,
                'model'=>$this->block_m,
                'block'=>$block
            ));
        }
        
        if( $block AND $form->submitted() )
        {
            if( $form->save() )
            {
                $form->response('success', array(
                    'add'=>'Блок добавлен',
                    'edit'=>'Изменения сохранены'
                ));
            }
            else
            {
                $form->response('error');   
            }
        }
        
        $this->render('form', array(
            'blocks_list'=>$blocks_list,
            'block'=>$block,
            'form'=>$form,
        ));
    }
    
    /**
     * Изменение порядка блоков
     */
    function action_update_blocks_order()
    {
        $ids = $this->input->post('ids');
        $area_alias = $this->input->post('area');

        $i=1;
        foreach($ids as $id)
        {
            $this->block_m
                            ->by_id($id)
                            ->set('order', $i)
                            ->set('area_alias', $area_alias)
                            ->update();
            $i++;
        }
    }
}