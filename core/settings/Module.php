<?php

class Module extends Modules\Addons\Entity
{
    public $url = 'settings';
    public $name = 'Настройки';
    public $description = '';
    public $is_backend = 1;
    public $is_core = 1;
    public $is_menu = 1;
    public $group = 'manage';
    
    function install()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('settings') ." (
              `id` varchar(50) NOT NULL,
              `module` varchar(30) NOT NULL,
              `value` mediumtext NOT NULL,
              PRIMARY KEY (`id`,`module`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ");
        
        CI::$APP->settings = new \Settings\Items;
    }
}