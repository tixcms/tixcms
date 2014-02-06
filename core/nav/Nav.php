<?php

class Nav extends Modules\Addons\Entity
{
    public $name = 'Навигация';
    public $description = 'Модуль позволяет создавать навигационные меню';
    public $url = 'nav';
    public $is_backend = 1;
    public $is_menu = 1;
    public $is_core = 1;
    public $group = 'view';
    public $author = 'TixCMS';

    function versions()
    {
        return array('0.37', '0.433', '0.48');
    }
    
    function update_to_0_48()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('nav') ." ADD  `new_window` TINYINT( 1 ) UNSIGNED NOT NULL");
    }
    
    function update_to_0_433()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('nav') ." ADD  `access` VARCHAR( 255 ) NOT NULL");
    }
    
    function update_to_0_37()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('nav') ." ADD  `type` VARCHAR( 25 ) NOT NULL AFTER  `parent_id`");
    }

    function install()
    {        
        $this->db->query("
            CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('nav') ." (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `parent_id` int(10) unsigned NOT NULL,
              `area_alias` varchar(25) NOT NULL,
              `url` varchar(255) NOT NULL,
              `attributes` varchar(255) NOT NULL,
              `name` varchar(50) NOT NULL,
              `status` tinyint(1) unsigned NOT NULL,
              `order` tinyint(3) unsigned NOT NULL,
              PRIMARY KEY (`id`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('nav_areas') ." (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `alias` varchar(25) NOT NULL,
              `name` varchar(25) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
        ");
    }
}