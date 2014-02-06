<?php

namespace Users\Forms\Inputs;

/**
 * Элемент формы для загрузки аватара пользователя
 */
class Avatar extends \Form\Input\File\Image\Simple
{
    public $label = 'Аватар';
    public $config = array(
        'upload_path'=>'uploads/users/avatars/',
        'allowed_types'=>'gif|png|jpg',
        'encrypt_name'=>TRUE
    );
    
    public $required = FALSE;
    
    public $max_width = 100;
    public $max_height = 100;
    
    function init()
    {
        parent::init();
        
        if( $this->form->is_update() AND !$this->form->entity->{$this->field} )
        {
            $this->form->inputs[$this->field]->value = $this->form->entity->avatar_url;
        }
    }
    
    function after_upload()
    {
        $this->load->library('image_lib');
        
        // если изображение больше макс. нужной ширины уменьшаем его
        $sizes = getimagesize($this->data['full_path']);
        $width = $sizes[0];
        $height = $sizes[1];
        
        // уменьшаем фото
        if( $width > $this->max_width OR $height > $this->max_height )
        {
            $config = array(
                'image_library'=>'gd2',
                'source_image'=>$this->data['full_path'],
                'new_image'=>$this->data['full_path'],
                'maintain_ratio'=>TRUE
            );
            
            if( $width < $height )
            {
                $ratio = $width/$this->max_width;
                $config['width'] = $this->max_width;
                $config['height'] = (int)$height/$ratio;
            }
            else
            {
                $ratio = $height/$this->max_height;
                $config['height'] = $this->max_height;
                $config['width'] = (int)$width/$ratio;
            }
            
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();
            
            $config = array(
                'image_library'=>'gd2',
                'source_image'=>$this->data['full_path'],
                'new_image'=>$this->data['full_path'],
                'width'=>$this->max_width,
                'height'=>$this->max_height,
                'maintain_ratio'=>FALSE
            );
            
            //$config['x_axis'] = 100;
            //$config['y_axis'] = 100;
            
            $this->image_lib->initialize($config);
            $this->image_lib->crop();
        }
    }
}