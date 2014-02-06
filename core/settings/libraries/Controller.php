<?php

namespace Settings;

class Controller
{    
    function run()
    {        
        $permissions_class = ucfirst($this->module->url()) .'\Permissions';
        $permissions = class_exists($permissions_class) ? new $permissions_class : false;
        $permissions = $permissions ? $permissions->get() : false;
        
        if( isset($permissions['settings']) AND !$this->user->can_access($this->module->url() .'_settings') )
        {
            $this->di->alert->set_flash('attention', 'У вас нет доступа к настройкам');
            
            $this->di->url->redirect('admin/'. $this->module->url());
        }
        
        $form = \Settings\Helper::get_settings_class($this->module->url, array(
            'model'=>$this->settings_m,
            'module'=>$this->module->url
        ));
        
        if( !$form )
        {
            show_404();
        }
        
        if( $form->submitted() AND $this->input->is_ajax_request() )
        {
            if( $form->validate() )
            {
                $form->save();
                
                $message = 'Настройки сохранены';
                
                echo json_encode(array(
                    'type'=>'success',
                    'text'=>$message,
                    'onSuccess'=>array(
                        'action'=>'notify'
                    ),
                    'images'=>$form->get_images_src()
                ));          
            }
            else
            {
                echo json_encode(array(
                    'type'=>'error',
                    'text'=>$form->get_errors(),
                    'errors'=>$form->get_errors('array')
                ));
            }
            
            return; 
        }

        $this->di->assets->css('settings::style.css');

        $this->template->render('settings::index', array(
            'form'=>$form
        ));
    }    
    
    function __get($name)
    {
        return \CI::$APP->$name;
    }
}