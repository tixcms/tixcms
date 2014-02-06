<?php

class Email extends Modules\Addons\Entity
{
    public $url = 'email';
    public $name = 'Почта';
    public $description = '';
    public $is_backend = 1;
    public $is_core = 1;
    public $is_menu = 1;
    public $group = 'manage';
    
    function versions()
    {
        return array(
            '0.33',
            '0.37'            
        );
    }
    
    function update_to_0_37()
    {
        $this->db->query("DROP TABLE IF EXISTS ". $this->db->dbprefix('email_templates_vars'));
        
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('email_templates') ." DROP  `name`");
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('email_templates') ." DROP  `description`");
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('email_templates') ." DROP  `position`");
    }            
    
    function update_to_0_33()
    {
        $this->db->query("DROP TABLE IF EXISTS ". $this->db->dbprefix('email_sent'));
        $this->db->query("CREATE TABLE  ". $this->db->dbprefix('email_sent') ." (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `user_id` INT UNSIGNED NOT NULL,
        `from` VARCHAR( 100 ) NOT NULL ,
        `to` VARCHAR( 250 ) NOT NULL ,
        `subject` VARCHAR( 255 ) NOT NULL ,
        `message` TEXT NOT NULL ,
        `created_on` INT UNSIGNED NOT NULL,
        `count` INT UNSIGNED NOT NULL
        ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;");
        
        $this->db->query("DROP TABLE IF EXISTS ". $this->db->dbprefix('email_queue'));
        $this->db->query("CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('email_queue') ." (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `from` varchar(255) NOT NULL,
          `to` varchar(255) NOT NULL,
          `subject` varchar(255) NOT NULL,
          `message` text NOT NULL,
          `type` enum('text','html') NOT NULL,
          `created_on` int(10) unsigned NOT NULL,
          `priority` tinyint(1) unsigned NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
    }
    
    function install()
    {        
        $this->db->query("CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('email_templates') ." (
              `alias` varchar(50) NOT NULL,
              `module` VARCHAR( 25 ) NOT NULL,
              `name` varchar(255) NOT NULL,
              `description` varchar(255) NOT NULL,
              `subject` varchar(255) NOT NULL,
              `from` varchar(100) NOT NULL,
              `text` text NOT NULL,            
              `position` INT UNSIGNED NOT NULL,
              PRIMARY KEY (`alias`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
            
        $this->db->query("CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('email_templates_vars') ." (
              `template_alias` varchar(50) NOT NULL,
              `alias` varchar(255) NOT NULL,
              `name` varchar(50) NOT NULL,
              `desc` varchar(255) NOT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    }
}