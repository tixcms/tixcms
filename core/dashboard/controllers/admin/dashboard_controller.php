<?php

class Dashboard_Controller extends Admin\Controller 
{
    public $has_settings = TRUE;
    
    function action_index()
    {
        $groups = \Modules\Helper::get_groups();
        $modules_by_groups = \Modules\Helper::get_modules_by_groups();
        
        $this->render(array(
            'groups'=>$groups,
            'modules_by_groups'=>$modules_by_groups
        ));
    }
    
    function action_adjust()
    {
        $groups = \Modules\Helper::get_groups();
        $modules_by_groups = \Modules\Helper::get_modules_by_groups(false);
        
        $this->assets->js('jquery::ui/sortable.min.js');
        
        $this->render(array(
            'groups'=>$groups,
            'modules_by_groups'=>$modules_by_groups
        ));
    }
    
    function action_profile()
    {
        $form = $this->load->library('Users\Forms\Backend\Profile', array(
            'entity'=>$this->user,
            'model'=>$this->users_m
        ));
        
        unset($form->actions['submit']);
        
        $apply = $form->actions['apply'];
        unset($form->actions['apply']);
        $form->actions['back_url'] = array(
            'href'=>'admin/dashboard',
            'label'=>'Обратно',
            'attrs'=>array(
                'class'=>'btn'
            )
        );        
        $form->actions['apply'] = $apply;
        $form->actions['apply']['value'] = 'Сохранить';
        
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
        
        $this->render(array(
            'form'=>$form
        ));
    }
}