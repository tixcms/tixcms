<?php

class Permissions_Controller extends Users\Admin\Controller
{
    function __construct()
    {
        parent::__construct();
        
        // модели
        $this->load->model('users/groups_m');
        $this->load->model('modules/modules_m');
    }
    
    function action_index()
    {
        // группы
        $groups = $this->groups_m->order_by('default')->where('alias !=', 'guests')->get_all();
        $headings = $this->get_table_headings($groups);
        array_unshift($headings, '');
        
        $permissions = $this->permissions_m->get_all();
        $permissions = $this->get_permissions_by_group($permissions);
        
        $modules = $this->modules_m->by_is_backend(1)->order_by('id', 'ASC')->get_all();
        
        $sub_permissions = array();
        foreach($modules as $module)
        {            
            $class = ucfirst($module->url) .'\Permissions';
            
            if( class_exists($class) )
            {
                $class = new $class;
                $sub_permissions[$module->url] = $class->get();
            }
        }
        
        $this->render('permissions/index', array(
            'groups'=>$groups,
            'headings'=>$headings,
            'permissions'=>$permissions,
            'modules'=>$modules,
            'sub_permissions'=>$sub_permissions
        ));    
    }
    
    /**
     * Сохранение прав
     */
    function action_save()
    {
        if( $this->input->post() )
        {
            $permissions = $this->input->post('groups');
            
            if( $groups = $this->groups_m->by_default(0)->get_all() )
            {
                foreach($groups as $group)
                {
                    $temp = array();
                    if( isset($permissions[$group->alias]['modules']) )
                    {
                        foreach($permissions[$group->alias]['modules'] as $key=>$value)
                        {
                            $temp[$key] = 1;
                        }
                    }
                    $permissions[$group->alias]['modules'] = $temp;
                    
                    $this->permissions_m
                                    ->by_group_alias($group->alias)
                                    ->set(
                                        'permissions', 
                                        isset($permissions[$group->alias]['modules'])
                                            ? serialize($permissions[$group->alias]['modules']) 
                                            : ''
                                    )
                                    ->update();
                    
                }
            }
        }
        
        $message = 'Изменения сохранены';
            
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
            $this->alert_flash('success', $message);
        }
        
        $this->redirect('admin/users/permissions');
    }
    
    private function get_permissions_by_group($permissions)
    {
        $result = array();
        if( $permissions )
        {
            foreach($permissions as $item)
            {
                $item->permissions = unserialize($item->permissions);
                $result[$item->group_alias] = $item;
            }
        }
        
        return $result;
    }
    
    function get_table_headings($groups)
    {
        return Helpers\CArray::map($groups, 'alias', 'name');
    }
}