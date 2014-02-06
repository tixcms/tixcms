<?php

class ThemeModule extends \Modules\Addons\Entity
{
    public $url = 'theme';
    public $name = 'Тема по-умолчанию';
    public $description = '';
    public $is_backend = 1;
    public $group = 'view';
    public $is_menu = 0;
    public $version = '0.12';
    
    function install()
    {        
        $this->db->query("INSERT INTO ". $this->db->dbprefix('blocks_areas') ." (`alias`, `name`) VALUES
            ('sidebar', 'Боковая колонка'),
            ('under-content', 'Под контентом'),
            ('footer', 'Подвал');"); 
            
            
        $this->db->query("INSERT INTO ". $this->db->dbprefix('nav_areas') ." (`alias`, `name`) VALUES
            ('header', 'Шапка'),
            ('navbar-left', 'Навигация слева'),
            ('navbar-right', 'Навигация справа'),
            ('sidebar', 'Боковая колонка'),
            ('footer', 'Подвал');
        ");
    }
}