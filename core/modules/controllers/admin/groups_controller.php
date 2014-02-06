<?php

class Groups_Controller extends Modules\Controller 
{
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('modules_groups_m');
        $this->load->model('modules_m');
    }
    
    function action_index()
    {
        $groups = $this->modules_groups_m->order_by('position')->get_all();
        
        $this->di->assets->js('jquery::ui/sortable.min.js');
        
        $this->render(array(
            'groups'=>$groups
        ));
    }
    
    function action_reorder()
    {
        $ids = $this->input->post('ids');
        
        if( $ids )
        {
            $i=1;
            foreach($ids as $id)
            {
                $this->modules_groups_m->set('position', $i)->by_id($id)->update();
                $i++;
            }
        }
        
        echo json_encode(array(
            'type'=>'success',
            'text'=>'Порядок сохранен'
        ));
    }
    
    function action_move_module($module_id, $group_alias)
    {
        $last_module = $this->modules_m->by_group_alias($group_alias)->order_by('position', 'ASC')->get_one();
        $position = $last_module ? $last_module->position + 1: 1;
        
        $this->modules_m
                    ->by_id($module_id)
                    ->set_group_alias($group_alias)
                    ->set_position($position)
                    ->update();
    }
    
    function action_modules_sort()
    {
        $groups = $this->modules_groups_m->order_by('position', 'ASC')->get_all();
        $temp = new stdClass;
        $temp->id = 0;
        $temp->alias = 'no_group';
        $temp->name = 'Без группы';
        
        if( $groups )
        {
            array_unshift($groups, $temp);
        }
        else
        {
            $groups[] = $temp;
        }

        $modules = $this->modules_m->order_by('position', 'ASC')->by_is_backend(1)->get_all();
        
        $modules_by_groups = array();
        if( $modules )
        {
            foreach($modules as $item)
            {
                $modules_by_groups[$item->group_alias][] = $item;
            }
        }
        
        $this->di->assets->js('jquery::ui/sortable.min.js');
        //$this->di->assets->js('jquery::ui/droppable.min.js');
        
        $this->render(array(
            'groups'=>$groups,
            'modules_by_groups'=>$modules_by_groups
        ));
    }
    
    function action_add()
    {
        $name = $this->input->post('name');
        $alias = $this->input->post('alias');
        
        $last_group = $this->modules_groups_m->select_max('position')->get_one();
        $position = $last_group ? $last_group->position + 1 : 1;
        
        $new_id = $this->modules_groups_m->insert(array(
            'name'=>$name,
            'alias'=>$alias,
            'position'=>$position
        ));
        
        if( $this->is_ajax() )
        {
            echo json_encode(array(
                'type'=>'success',
                'text'=>'Группа создана',
                'id'=>$new_id
            ));
        }
        else
        {
            $this->referer();
        }
    }
    
    function action_delete($id)
    {
        $this->modules_groups_m->by_id($id)->delete();
        $this->modules_m->by_group_id($id)->set('group_id', 0)->update();
        
        echo json_encode(array(
                'type'=>'success',
                'text'=>'Группа удалена'
            ));
    }
}