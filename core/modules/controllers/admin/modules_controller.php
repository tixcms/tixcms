<?php

class Modules_Controller extends Modules\Controller 
{
    public $has_help = true;
    
    function __construct()
    {
        parent::__construct();
        
        $this->load->model('modules_m');
        
        // библиотека
        $this->load->library('Modules\Addons', '', 'addons');
    }
    
    /**
     * Список модулей
     */
    function action_index($plain = false)
    {
        // установленные допонления
        $installed_addons = $this->modules_m->by_is_core(0)->get_all();
        
        // модули ядра
        $core_modules = $this->modules_m->where('name !=', 'app')->by_is_core(1)->get_all();
        
        // дополнения в папке
        $addons_in_folder = $this->addons->get_in_folder();
        
        // неустановленные дополнения
        $uninstalled_addons = $this->addons->get_uninstalled($installed_addons, $addons_in_folder);
        
        // версия в папке
        $new_core_version = $this->addons->get_new_core_version();
        
        $data = array(
            'installed_addons'=>$installed_addons,
            'uninstalled_addons'=>$uninstalled_addons,
            'core_modules'=>$core_modules,
            'new_core_version'=>$new_core_version,
            'all'=>$addons_in_folder
        );
        
        if( $plain )
        {
            $data['core_version'] = $this->version;
            echo $this->template->view('index', $data);
        }
        else
        {
            $this->render($data);
        }
    }
    
    /**
     * Сохраняет позиции модулей
     */
    function action_reorder()
    {
        $this->load->model('modules_groups_m');
        
        $ids = $this->input->post('ids');
        $group_id = $this->input->post('group_id');
        
        if( !$ids )
        {
            return;
        }
        
        if( $group_id == 0 )
        {
            $group_alias = 'no_group';
        }
        else
        {
            $group = $this->modules_groups_m->by_id($group_id)->get_one();
            $group_alias = $group->alias;
        }
        
        $i=1;
        foreach($ids as $id)
        {
            if( is_numeric($id) )
            {
                $this->modules_m->by_id($id)->set('position', $i)->set_group_alias($group_alias)->update();
                $i++;
            }
        }
        
        echo json_encode(array(
            'type'=>'success',
            'text'=>'Изменения сохранены'
        ));
    }
    
    /**
     * Включение, выключение модуля
     */
    function action_status($id)
    {
        if( $this->is_ajax() )
        {
            if( !$module = $this->modules_m->by_id($id)->get_one() )
            {
                show_404();
            }
            
            $this->modules_m->by_id($id)->set_is_active(!$module->is_active)->update();
            
            $message = 'Модуль '. (!$module->is_active ? 'включен' : 'отключен');
        
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
     * Включение, выключение пункта меню
     */
    function action_is_menu_toggle($id)
    {
        $module = $this->modules_m->by_id($id)->get_one();
        
        $this->modules_m->by_id($id)->set('is_menu', !$module->is_menu)->update();
        
        $message = 'Пункт меню '. (!$module->is_menu ? 'включен' : 'отключен');
        
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