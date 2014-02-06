<?php

namespace HTML\Blocks\Forms;

class Block extends \Block\Form
{
    private $use_codemirror = false;
    
    function init()
    {
        if( class_exists('Codemirror') )
        {
            $this->use_codemirror = true;            
            
            $codemirror = new \Codemirror;
            $codemirror->set_mode('html')->add_to_assets();
        }
        
        parent::init();
    }
    
    function inputs()
    {
        return array(
            'html'=>array(
                'type'=>'textarea',
                'label'=>'Текст или HTML код',
                'rules'=>'trim|required',
                'after_input'=>$this->use_codemirror ? '<script>var myCodeMirror = CodeMirror.fromTextArea(document.getElementById("codemirror"));</script>' : '',
                'attrs'=>array(
                    'id'=>$this->use_codemirror ? 'codemirror' : ''
                ),
                'xss'=>false
            )
        );
    }
}