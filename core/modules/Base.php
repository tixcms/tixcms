<?php

class Base extends Modules\Addons\Entity
{
    public $url = 'modules';
    public $name = 'Дополнения';
    public $description = '';
    public $is_backend = 1;
    public $is_core = 1;
    public $is_menu = 1;
    public $group = 'manage';
    
    function versions()
    {
        return array(
            '0.2.88',
            '0.2.89',
            '0.2.99',
            '0.31',
            '0.35',
            '0.36',
            '0.4',
            '0.411',
            '0.433'
        );
    }
    
    function update_to_0_433()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('modules') ." DROP COLUMN is_active");
    }
    
    function update_to_0_411()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('modules') ." CHANGE  `name`  `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('modules') ." CHANGE  `desc`  `desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
    }
    
    function update_to_0_4()
    {
        $columns = $this->db->query("SHOW columns FROM ". $this->db->dbprefix('modules') ."");
        $columns = $columns->result();
        
        $columns = \Helpers\CArray::map($columns, 'Field', 'Field');
        
        if( !in_array('is_service', $columns) )
        {
            $this->db->query("ALTER TABLE  ". $this->db->dbprefix('modules') ." ADD  `is_service` TINYINT( 1 ) UNSIGNED NOT NULL AFTER  `is_backend`");
        }
    }
    
    function update_to_0_36()
    {
        $this->db->query("UPDATE ". $this->db->dbprefix('modules') ." SET is_core = 0, version = '0.1' WHERE url = 'app'");
    }
    
    function update_to_0_35()
    {
        $this->db->query("DELETE FROM ". $this->db->dbprefix('modules') ." where url = 'nav' AND is_core = 1;");
    }
    
    function update_to_0_2_99()
    {
        $this->db->query("INSERT INTO ". $this->db->dbprefix('modules_groups') ." (`alias`, `name`, `position`) VALUES
            ('addons', 'Дополнения', 2),
            ('view', 'Внешний вид', 3),
            ('content', 'Содержание', 1),
            ('manage', 'Управление', 4);"
        );
    }
    
    function install()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('modules') ." (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `group_alias` varchar(20),
              `name` varchar(50) NOT NULL,
              `desc` varchar(255) NOT NULL,
              `url` varchar(20) NOT NULL,
              `version` varchar(10) NOT NULL,
              `author` VARCHAR( 25 ) NOT NULL,
              `is_core` tinyint(1) NOT NULL,
              `is_frontend` tinyint(1) NOT NULL,
              `is_backend` tinyint(1) NOT NULL,
              `is_service` tinyint(1) NOT NULL,
              `is_active` tinyint(1) NOT NULL,
              `is_menu` tinyint(1) NOT NULL,
              `position` INT UNSIGNED NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
        ");
        
        $this->db->query("CREATE TABLE  ". $this->db->dbprefix('modules_groups') ." (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
            `alias` VARCHAR( 20 ) NOT NULL,
            `name` VARCHAR( 50 ) NOT NULL ,
            `position` INT UNSIGNED NOT NULL
            ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;"
        );
        
        $this->db->query("CREATE TABLE ". $this->db->dbprefix('sessions') ." (
              `session_id` varchar(40) NOT NULL DEFAULT '0',
              `ip_address` varchar(45) NOT NULL DEFAULT '0',
              `user_agent` varchar(120) NOT NULL,
              `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
              `user_data` text NOT NULL,
              PRIMARY KEY (`session_id`),
              KEY `last_activity` (`last_activity`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ");
        
        $this->db->query("CREATE TABLE ". $this->db->dbprefix('core_version') ." (
            `version` VARCHAR( 10 ) NOT NULL
            ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;");
    }
}