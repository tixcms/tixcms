<?php

class Users_Controller extends Users\Admin\Controller
{
    function __construct()
    {
        parent::__construct();

        // модели
        $this->load->model(array(
            'users/users_m', 
            'users/groups_m'
        ));
        
        $this->users_m->set_is_moderated(1)->update();
    }

    /**
     * Список пользователей
     */
    function action_index($type = 'all')
    {
        $table = $this->load->library('Users\Tables\User', array(
            'model'=>$this->users_m,
            'per_page'=>10
        ));
        
        if( $this->is_ajax() )
        {
            echo $table->render('json');
                
            return;
        }

        $this->render('index', array(
            'table'=>$table
        ));
    }
    
    /**
     * Отмечаем что пользователь проверен
     */
    function action_moderated($user_id)
    {
       $this->users_m->where('id', $user_id)->set('is_moderated', 1)->update();
       
       $this->alert_flash('success', 'Пользователь отмечен проверянным');
       
       $this->referer(); 
    }
    
    /**
     * Редактирование
     */
    function action_edit($id)
    {
        $item = $this->users_m->by_id($id)->get_one();
        
        if( $item->id == 0 )
        {
            show_error('Гостевую запись нельзя редактировать');
        }
        
        $this->crumb('Редактирование', 'admin/users/edit/'. $item->id);
        
        $this->template->add_layout('profile/layout');
        
        $this->template->set('user', $item);
        $this->template->set('tabs', $this->tabs($item->id));
        
        $this->form($item);
    }
    
    /**
     * Создание пользователя
     */
    function action_add()
    {
        $this->form();
    }

    /**
     * Создание и редактирование
     */
    function form($item = false)
    {
        $form = $this->load->library('Users\Forms\Backend\Profile', array(
            'entity'=>$item,
            'model'=>$this->users_m
        ));
        
        if( $form->submitted() )
        {
            if( $form->save() )
            {
                $form->response('success', array(
                    'edit'=>'Изменения сохранены',
                    'add'=>'Пользователь добавлен'
                ));
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
                    $form->response('error');
                }
            }   
        }

        $this->render('form', array(
            'item'=>$item,
            'form'=>$form
        ));
    }
    
    /**
     * Удаление
     */
    function action_delete($id)
    {
        if( !Security::check_csrf_token() )
        {
            show_404();
        }
        
        $user = $this->users_m->by_id($id)->get_one();
        $admins_count = $this->users_m->by_group_alias('admins')->count();
        
        // самого себя нельзя удалить
        if( $this->user->id == $id OR ($user->group_alias == 'admins' AND $admins_count == 1) )
        {
            if( $this->is_ajax() )
            {
                echo json_encode(array(
                    'type'=>'attention',
                    'text'=>'Удаление выполнить невозможно'
                ));
                
                return;
            }
            else
            {
                $this->alert_flash('attention', 'Удаление выполнить невозможно');
                
                $this->referer();
            }
        }
        
        // удаляем пользователя
        $this->users_m->by_id($id)->delete();
    
        $this->events->trigger('users.delete', array(
            'user'=>$user,
            'user_id'=>$id
        ));
            
        if( $this->is_ajax() )
        {
            echo json_encode(array(
                'type'=>'success',
                'text'=>'Пользователь удален'
            ));
        }
        else
        {
            $this->alert->set_flash('success', 'Пользователь удален');
            
            $this->referer();
        }
    }

    function action_mass_delete()
    {
        if( !Security::check_csrf_token() )
        {
            show_404();
        }
        
        if( !$this->input->post() )
        {
            show_404();
        }
        
        $ids = $this->input->post('ids');
        $deleted_ids = array();

        if( $ids )
        {
            foreach($ids as $id)
            {
                $user = $this->users_m->by_id($id)->get_one();
                $admins_count = $this->users_m->by_group_alias('admins')->count();
                
                if( $this->user->id == $id OR ($user->group_alias == 'admins' AND $admins_count == 1) )
                {
                    continue;
                }
                
                $deleted_ids[] = $id;
                
                $this->users_m->by_id($id)->delete();

                $this->events->trigger('users.delete', array(
                    'user'=>$user,
                    'user_id'=>$id
                ));
            }
        }

        if( $this->is_ajax() )
        {
            echo json_encode(array(
                'type'=>'success',
                'text'=>'Пользователи удалены',
                'deleted_ids'=>$deleted_ids
            ));
        }
        else
        {
            $this->alert->set_flash('success', 'Пользователи удалены');
            
            $this->referer();
        }
    }
}