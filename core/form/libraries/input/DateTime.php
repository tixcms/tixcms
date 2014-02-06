<?php

namespace Form\Input;

/**
 * Элемент выводит выпадающие списки для даты
 */
class DateTime extends \Form\Input
{
    public $error = 'Неверно введены данные в поле %s';
    
    /**
     * Файл вида
     */
    public $view = 'datetime';
    
    /**
     * Лейбл
     */
    public $label = 'Дата';
    
    /**
     * Массив номеров лет
     */
    public $years;
    
    /**
     * Месяцы
     */
    public $months;
    
    /**
     * Показывать часы и минуты
     */
    public $show_time = TRUE;
    
    /**
     * Показывать кнопку установки даты и времени на текущий момент
     */
    public $show_now_button = true;
    
    /**
     */
    public $values;
    
    /**
     */
    public $value = '';
    
    /**
     * Формат времени возвращаемого значения (date())
     */
    public $save_format = false;
    
    function init()
    {
        parent::init();
        
        if( !$this->value )
        {
            $this->value = isset($this->form->entity->{$this->field}) 
                ? $this->form->entity->{$this->field}
                : '';
        }
        
        if( !$this->years )
        {
            foreach(range(date('Y'), date('Y') - 100) as $year)
            {
                $this->years[$year] = $year;
            }
        }
        
        for($i=1; $i<13; $i++)
        {
            $this->months[$i] = \Helpers\Date::month($i, \Helpers\Date::MONTH_ROD);
        }
        
        $this->days = range(0, 31);
        unset($this->days[0]);
        
        $fields = array('year', 'month', 'day', 'hours', 'minutes');
   
        if( $this->form->submitted() )
        {            
            $this->values = array(
                'year'=>(int)$this->form->post($this->field . '_year'),
                'month'=>(int)$this->form->post($this->field . '_month'),
                'day'=>(int)$this->form->post($this->field . '_day'),
                'hours'=>(int)$this->form->post($this->field . '_hours'),
                'minutes'=>(int)$this->form->post($this->field . '_minutes')
            );
        }
        elseif( $this->form->is_update() OR $this->value )
        {
            if( is_numeric($this->value) )
            {
                $this->init_values_with_timestamp();
            }
            else
            {
                $this->init_values_with_mysql_date();
            }
        }
        else
        {
            foreach($fields as $field)
            {
                if( !isset($this->values[$field]) )
                {
                        $this->values[$field] = $this->form->submitted()
                                    ? set_value($this->field . '_'. $field)
                                    : $this->current($field);
                }
            }
        }
        
        $this->value = mktime(
            isset($this->values['hours']) ? $this->values['hours'] : 0, 
            isset($this->values['minutes']) ? $this->values['minutes'] : 0, 
            0, 
            $this->values['month'],
            $this->values['day'], 
            $this->values['year']
        );
    }
    
    function init_values_with_timestamp()
    {
        $this->values = array(
            'year'=>date('Y', $this->value),
            'month'=>date('n', $this->value),
            'day'=>date('j', $this->value),
            'hours'=>date('H', $this->value),
            'minutes'=>date('i', $this->value)
        );
    }
    
    function init_values_with_mysql_date()
    {
        if( strstr($this->value, ' ') !== FALSE )
        {
            list($date, $time) = explode(' ', $this->value);
            list($year, $month, $day) = explode('-', $date);
            list($hour, $minutes, $seconds) = explode('-', $time);
            
            $this->values = array(
                'hours'=>$hour,
                'minutes'=>$minutes
            );
        }
        else
        {
            list($year, $month, $day) = explode('-', $this->value);
        }
        
        $this->values['year'] = $year;
        $this->values['month'] = $month;
        $this->values['day'] = $day;
    }
    
    function current($field)
    {
        $map = array('year'=>'Y', 'month'=>'n', 'day'=>'j', 'hours'=>'H', 'minutes'=>'i');
        
        return date($map[$field]);
    }
    
    function validate()
    {
        $year = (int)$this->form->post($this->field . '_year');
        $month = (int)$this->form->post($this->field . '_month');
        $day = (int)$this->form->post($this->field . '_day');
        $hours = (int)$this->form->post($this->field . '_hours');
        $minutes = (int)$this->form->post($this->field . '_minutes');
        
        if( !in_array($year, $this->years) )
        {
            $this->error = 'Неверно указан год';
            
            return false;
        }
        
        if( !key_exists($month, $this->months) )
        {
            $this->error = 'Неверно указан месяц';
            
            return false;
        }
        
        if( !in_array($day, range(1, 31)) )
        {
            $this->error = 'Неверно указан день';
            
            return false;
        }
        
        if( !in_array($hours, range(0, 23)) )
        {
            $this->error = 'Неверно указаны часы';
            
            return false;
        }
        
        if( !in_array($minutes, range(0, 59)) )
        {
            $this->error = 'Неверно указаны минуты';
            
            return false;
        }
        
        if( !checkdate($month, $day, $year) )
        {
            $this->error = 'Такой даты не существует';
            
            return false;
        }
        
        $return_data = mktime($hours, $minutes, 0, $month, $day, $year);
        
        if( $this->save_format )
        {
            $return_data = date($this->save_format, $return_data);
        }
        
        $this->form->set($this->field, $return_data);
        
        return true;
    }
}