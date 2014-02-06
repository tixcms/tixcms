<?php

namespace Form\Input;

class Url extends \Form\Input
{    
    public $source_input;
    public $url_prepend;
    
    function validate($str)
    {
        // если пользователь не ввел урл, то генерируем из соответствующего поля
        if( !$this->form->get($this->field) AND $this->source_input )
        {
            $url = $this->form->get($this->source_input);
        }
        else
        {
            $url = $this->form->get($this->field);
        }
        
        // транслит и нижний регистр
        $url = strtolower($this->form->di->string->url_translit($url));
        
        if( $this->is_need_to_check_url_unique() )
        {
            // если пользователь ввел урл вручную, то проверяем, что не совпадает с уже имеющимися
            if( $this->form->get($this->field) )
            {            
                if( $this->form->model->{'by_'. $this->field}($url)->count() )
                {
                    $this->error = 'Такой адрес ссылки уже используется';
                    
                    return false;
                }
            }
            // если автоматическая генерация, то добавляем числовой суффикс при совпадении
            else
            {
                $i=1;
                $orig_url = $url;
                while($this->form->model->{'by_'. $this->field}($url)->count())
                {
                    $url = $orig_url . '-'. $i;
                    $i++;
                }
            }
        }
        
        // устанавиливаем значение поля
        $this->form->set($this->field, $url);
        
        return true;
    }
    
    function is_need_to_check_url_unique()
    {
        return $this->form->is_insert() 
            OR ( 
                $this->form->is_update() 
                AND $this->form->entity->{$this->field} != $this->form->get($this->field) 
            );
    }
}