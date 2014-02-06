<?php

class Templates_m extends Tix\Model
{
    public $table = 'email_templates';
    public $primary_key = 'alias';
    
    function get($module, $alias)
    {
        $email_class = ucfirst($module) . '\Emails';
        
        if( !class_exists($email_class) )
        {
            return false;
        }        
        
        $email_class = $this->load->library($email_class);
        
        $templates = $email_class->data();
        
        $default_entity = (object)$templates[$alias];
        
        $default_entity->module = $module;
        $default_entity->alias = $alias;
        
        if( $entity = $this->by_module($module)->by_alias($alias)->get_one() )
        {
            $entity->vars = $default_entity->vars;
        }
        else
        {
            $entity = $default_entity;
        }
        
        return $entity;
    }
    
    function get_by_module($module)
    {
        $email_class = ucfirst($module) . '\Emails';
        
        if( !class_exists($email_class) )
        {
            return false;
        }        
        
        $email_class = $this->load->library($email_class);
        
        return $email_class->data();
    }
}