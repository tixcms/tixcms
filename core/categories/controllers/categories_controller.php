<?php

class Categories_Controller extends \Tix\Controllers\CLI
{
    function action_recount($module = false, $model_path = false, $model_alias = false, $cat_field = false, $filter = false)
    {
        $this->load->database();
        $this->load->library('Settings\Items', '', 'settings');  
        
        if( $module AND $model_path AND $model_alias AND $cat_field )
        {
            \Categories\Helper::items_recount($module, str_replace('.', '/', $model_path), $model_alias, $cat_field, $filter);
            
            echo "Items has been recounted\n";
        }
        else
        {
            echo "Missing arguments: \$module, \$model_path, \$model_alias, \$cat_field\n";
        }
    }
}