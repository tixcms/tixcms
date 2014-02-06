<?php

class FormBase extends Modules\Addons\Entity
{
    public $url ='form';
    public $name = 'Формы';
    public $is_core = 1;
    public $is_backend = 1;
    public $group = 'manage';
    
    function versions()
    {
        return array(
            '0.412',
            '0.432',
            '0.434',
            '0.442'
        );
    }
    
    function update_to_0_442()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('form_editor') ." (
              `id` varchar(255) NOT NULL,
              `elements` text NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

        ");
    }
    
    function update_to_0_434()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('forms') ." (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `alias` varchar(50) NOT NULL,
              `name` varchar(255) NOT NULL,
              `inputs` text NOT NULL,
              `email` varchar(255) NOT NULL,
              `success_message` varchar(500) NOT NULL,
              PRIMARY KEY (`id`),
              KEY `alias` (`alias`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
        ");
    }
    
    function update_to_0_432()
    {
        $this->db->query("UPDATE  ". $this->db->dbprefix('modules') ." SET  `is_service` =  '1', is_menu = 1, group_alias = 'manage' WHERE  `url` = 'form'");
        $this->add_to_modules();
    }
    
    function update_to_0_412()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('tags') ." (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `module` varchar(50) NOT NULL,
          `item_id` INT UNSIGNED NOT NULL,
          `tag` varchar(255) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
    }
}