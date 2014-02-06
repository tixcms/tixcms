<?php

class Block extends Modules\Addons\Entity
{
    public $url ='block';
    public $name = 'Блоки';
    public $description = '';
    public $is_backend = 1;
    public $is_core = 1;
    public $is_menu = 1;
    public $group = 'view';
    
    function versions()
    {
        return array('0.37', '0.433');
    }
    
    function update_to_0_433()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('blocks') ." ADD  `access` VARCHAR( 255 ) NOT NULL");
    }
    
    function update_to_0_37()
    {
        $this->db->query("DROP TABLE IF EXISTS ". $this->db->dbprefix('blocks'));
        $this->db->query("RENAME TABLE ". $this->db->dbprefix('blocks_instances') ." TO ". $this->db->dbprefix('blocks'));
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('blocks') ." CHANGE  `block_id`  `block_alias` VARCHAR( 50 ) NOT NULL");
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('blocks') ." ADD  `block_module` VARCHAR( 50 ) NOT NULL AFTER  `block_alias`");
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('blocks') ." ADD  `block_class` VARCHAR( 50 ) NOT NULL AFTER  `block_module`");
    }
    
    function install()
    {        
        $this->db->query("CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('blocks') ." (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `module` VARCHAR( 30 ) NOT NULL,
          `name` varchar(255) NOT NULL,
          `class` varchar(50) NOT NULL,
          `description` text NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

        $this->db->query("CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('blocks_areas') ." (
          `id` int(25) unsigned NOT NULL AUTO_INCREMENT,
          `alias` varchar(25) NOT NULL,
          `name` varchar(25) NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `alias` (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;"); 
        
        $this->db->query("CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('blocks_instances') ." (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `block_id` int(10) unsigned NOT NULL,
          `area_alias` varchar(25) NOT NULL,
          `title` varchar(255) NOT NULL,
          `order` int(10) unsigned NOT NULL,
          `data` text NOT NULL,
          `show_title` tinyint(1) unsigned NOT NULL,
          `active` tinyint(1) unsigned NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
    }
}