<?php

class Editor_Controller extends \Admin\Controller
{
    public function action_get_element($element_type, $element_index, $editor_name)
    {
        $generator = new \Form\Input\Editor\Generator;
        $generator->set_element($element_type, $element_index, $editor_name);
        
        echo $generator->run();
    }
}