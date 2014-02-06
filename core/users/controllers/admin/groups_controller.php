<?php

class Groups_Controller extends Users\Admin\Controller
{
    function __construct()
    {
        parent::__construct();
        
        // модель
        $this->load->model('groups_m');
    }
    
    /**
     * Список групп
     */
    function action_index()
    {
        // группы
        $groups = $this->groups_m->where('alias !=', 'guests')->get_all();
        
        $this->render('groups/index', array(
            'groups'=>$groups
        ));
    }
    
    /**
     * Удаление группы
     */
    function action_delete($id)
    {
        $group = $this->groups_m->by_id($id)->get_one();
        
        // удаляем если не дефолтная
        if( !$group->default )
        {
            // перемещаем всех пользователей в другую группу
            $this->users_m->by_group_alias($group->alias)->set_group_alias('users')->update();
            
            $this->groups_m->by_alias($group->alias)->delete();
            
            // удаляем запись прав
            $this->permissions_m->by_group_alias($group->alias)->delete();
            
            if( $this->is_ajax() )
            {
                echo json_encode(array(
                    'type'=>'success',
                    'text'=>'Группа удалена'
                ));
            }
            else
            {
                // уведомление
                $this->alert_flash('success', 'Группа удалена');
                
                // редирект
                $this->redirect('users/admin/groups');
            }
        }
        else
        {
            // уведомление
            $this->alert_flash('error', 'Эту группу нельзя удалить');
            
            // редирект
            $this->redirect('users/admin/groups');
        }        
    }
    
    /**
     * Редактирование группы
     */
    function action_edit($id)
    {
        // группа
        $group = $id ? $this->groups_m->by_id($id)->get_one() : FALSE;
        
        // дефолтную нельзя редактировать
        if( $group AND $group->default == 1 )
        {
            $this->redirect('admin/users/groups');
        }
        
        $this->form($group);
    }
    
    /**
     * Добавление и редактирование группы
     */
    function action_add()
    {
        $this->form(FALSE);
    }
    
    /**
     * Добавление и редактирование группы
     */
    function form($group)
    {        
        $form = new Users\Forms\Backend\Group(array(
            'entity'=>$group, 
            'model'=>$this->groups_m
        ));
        
        // обрабатываем форму
        if( $form->submitted() )
        {
            // валидация
            if( $form->save() )
            {
                $message = $form->is_insert() ? 'Группа добавлена' : 'Изменения сохранены';
                
                if( $this->is_ajax() )
                {
                    echo json_encode(array(
                        'type'=>'success',
                        'text'=>$message
                    ));
                    
                    return;
                }
                
                // уведомление
                $this->alert_flash('success', $message);
                
                // редирект
                $this->redirect('users/admin/groups');
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
                
                $this->alert('error', $form->get_errors());
            }
        }
        
        $this->render('groups/form', array(
            'form'=>$form
        ));
    }
}