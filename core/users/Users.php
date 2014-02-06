<?php

class Users extends Modules\Addons\Entity
{
    public $url = 'users';
    public $name = 'Пользователи';
    public $description = '';
    public $is_backend = 1;
    public $is_service = 1;
    public $is_core = 1;
    public $group = 'manage';
    public $is_menu = 1;
    
    function versions()
    {
        return array(
            '0.2.97',
            '0.34.1',
            '0.34.22',
            '0.34.4',
            '0.4'
        );
    }
    
    function update_to_0_48()
    {
        \Helpers\File::make_path('uploads/users/');
        \Helpers\File::make_path('uploads/users/avatars/');
    }
    
    function update_to_0_4()
    {
        $this->db->query("UPDATE  ". $this->db->dbprefix('modules') ." SET  `is_service` =  '1' WHERE  `url` = 'users'");
    }

    function update_to_0_34_4()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('users') ." ADD  `settings` TEXT NOT NULL");
    }

    function update_to_0_34_22()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('users') ." ADD  `name` VARCHAR( 50 ) NOT NULL AFTER  `login`");
    }
    
    function update_to_0_34_1()
    {
        $this->load->model('users/users_m');
        
        $this->db->query("UPDATE ". $this->db->dbprefix('users') ." SET is_active = ". \Users_m::STATUS_NOT_ACTIVATED . " WHERE is_active = 0");
    }
    
    function update_to_0_2_97()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('users') ." ADD  `is_moderated` TINYINT( 1 ) NOT NULL");
    }
    
    function install()
    {        
        $this->db->query("CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('users_groups_permissions') ." (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `group_alias` varchar(50) NOT NULL,
              `permissions` mediumtext NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
            
        $this->db->insert_batch('users_groups_permissions', array(
            array(
                'group_alias'=>'admins',
                'permissions'=>''
            ),
            array(
                'group_alias'=>'users',
                'permissions'=>''
            ),
        ));

        $this->db->query("CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('users') ." (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `group_alias` varchar(25) NOT NULL,
          `login` varchar(25) NOT NULL,
          `password` char(40) NOT NULL,
          `signature` varchar(255) NOT NULL,
          `email` varchar(50) NOT NULL,
          `is_active` tinyint(1) NOT NULL,
          `activation_code` varchar(40) NOT NULL,
          `reset_token` varchar(40) NOT NULL,
          `register_date` int(11) unsigned NOT NULL,
          `lastvisit_date` int(11) unsigned NOT NULL,
          `last_ip` varchar(15) NOT NULL,
          `avatar` varchar(40) NOT NULL,
          `user_agent` varchar(500) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
        
        $this->db->insert('users', array(
            'group_alias'=>'guests',
            'login'=>'guest'
        ));
        
        $this->db->update('users', array(
            'id'=>0
        ));
        
        $this->db->query("ALTER TABLE ". $this->db->dbprefix('users') ." AUTO_INCREMENT = 1");
        
        $this->db->query("CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('users_groups') ." (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `alias` varchar(25) NOT NULL,
          `name` varchar(50) NOT NULL,
          `default` tinyint(1) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
        
        $this->db->insert_batch('users_groups', array(
            array(
                'alias'=>'admins',
                'name'=>'Администраторы',
                'default'=>1
            ),
            array(
                'alias'=>'users',
                'name'=>'Пользователи',
                'default'=>1
            ),
            array(
                'alias'=>'guests',
                'name'=>'Гости',
                'default'=>1
            ),
        ));
    }
}