<?php

namespace Form\Input;

class File extends \Form\Input
{
    /**
     * Конфиг
     */
    public $config = array();

    /**
     * Обязательно ли загрузка
     */
    public $required = true;

    /**
     * Значения по умолчанию
     */
    public $default_config = array(
        'upload_path'=>'uploads/',
        'allowed_types'=>'*',
        'translit'=>true,
        'delete_old'=>true,
        //'file_name'=>false,
        //'overwrite'=>true
    );

    public $label = 'Файл';

    /**
     * Класс загрузкчика файлов
     */
    public $uploader = false;

    /**
     * Данные загрузки
     */
    public $upload_data;

    /**
     * Флаг был ли загружен файл
     */
    public $uploaded = false;

    /**
     */
    public $view = 'file';

    function init()
    {
        parent::init();

        // устанавливаем аттрибут форме для загрузки файлов
        $this->form->attrs['upload'] = true;

        if( $this->form->is_update() AND !$this->value AND $this->value !== false AND $this->form->entity->{$this->field} )
        {
            $this->value = \URL::site_url($this->config['upload_path'] . $this->form->entity->{$this->field}, false);
        }
    }

    /**
     * Загрузка файла
     */
    function validate()
    {
        // если первая загрузка и нет файла и загрузка не обязательно
        if( $this->form->is_insert()
            AND ( !isset($_FILES[$this->field])
                OR $_FILES[$this->field]['error'] == UPLOAD_ERR_NO_FILE
            )
                AND !$this->required )
        {
            $this->form->un_set($this->field);

            return true;
        }

        // загружаем файл если первое добавление либо новый файл
        if( isset($_FILES[$this->field])
            AND (
                $this->form->is_insert()
                    OR ($this->form->is_update() AND $_FILES[$this->field]['error'] != UPLOAD_ERR_NO_FILE)
            )
        )
        {
            // устанавливаем значения конфига
            $this->config = array_merge($this->default_config, $this->config);

            // транслит имени файла
            if( !isset($this->config['file_name']) AND $this->config['translit'] )
            {
                $this->translit();
            }

            // устанавливаем загрузчик
            $this->set_uploader();

            // загружаем файл
            if( $this->uploader->do_upload($this->field) )
            {
                $this->uploaded = true;

                // данные файла
                $this->data = $this->uploader->data();

                // устанавливаем значение поля имя файла
                if( $this->save )
                {
                    $this->form->set($this->field, $this->data['file_name']);
                }

                // если нужно удаляем старый файл
                if( $this->config['delete_old'] AND !isset($this->config['overwrite']) )
                {
                    $this->delete_old();
                }

                // возможность пост обработки файла
                $this->after_upload();

                return true;
            }
            // ошибки загрузки
            else
            {
                $this->error = $this->uploader->display_errors('', '');

                return false;
            }
        }
        // новый файл не был загружен, ничего не меняем
        else
        {
            if( $this->input->is_ajax_request() AND $this->form->is_insert() )
            {
                $this->error = 'Файл не загружен';

                return false;
            }

            if( $this->save AND $this->form->is_update() )
            {
                $this->form->set($this->field, $this->form->entity->{$this->field});
            }

            return true;
        }
    }

    function set_uploader()
    {
        if( !$this->uploader )
        {
            $this->uploader = \CI::$APP->load->library('upload', array(), true);
            
            $this->uploader->initialize($this->config);
        }
    }

    /**
     * Метод вызывается после загрузки фото
     */
    function after_upload(){}

    /**
     * Транслитерация имена файла, для устранения ошибок с кириллическими именами
     */
    function translit()
    {
        $path_parts = pathinfo($_FILES[$this->field]['name']);

        $this->config['file_name'] = \Helpers\String::url_translit($path_parts['filename']);
    }

    /**
     * Удаление старого файла
     */
    function delete_old()
    {
        if( $this->form->is_insert() )
        {
            return false;
        }

        $file_path = $this->config['upload_path'] . $this->form->entity->{$this->field};

        if( file_exists($file_path) AND is_file($file_path) )
        {
            unlink($file_path);

            return true;
        }

        return false;
    }

    /**
     * Возвращает ошибки
     */
    function get_errors()
    {
        return $this->errors;
    }

    /**
     * Возвращает массив значений
     */
    function input()
    {
        return array(
            'type'=>'custom',
            'view'=>$this->view,
            'rules'=>'callback_valid_upload['. $this->field .']',
            'label'=>$this->label,
            'help'=>$this->help,
            'uploader'=>true,
            'save'=>false,
            'value'=>$this->value()
        );
    }

    function value()
    {
        return ($this->form->entity AND $this->form->entity->{$this->field} )
            ? \URL::site_url($this->config['upload_path'] . $this->form->entity->{$this->field}) : '';
    }

    function __set($name, $value)
    {
        $this->$name = $value;
    }
}