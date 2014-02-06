<?php

namespace Theme;

class BgInput extends \Form\Input
{
    public $input_folder = '';
    public $view = 'theme::bginput.php';
    public $options;
    public $folder;
    
    public function init()
    {
        parent::init();
        
        $this->options = $this->options();
    }
    
    public function validate()
    {
        return true;
    }
    
    public function options()
    {
        $files = glob('themes/theme/img/'. $this->folder .'/*.png');
        
        $options = array();
        foreach($files as $file)
        {
            $name = str_replace('.png', '', basename($file));
            
            $options[$name] = $name;
        }
        
        return $options;
    }    
}