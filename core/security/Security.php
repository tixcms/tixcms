<?php

class Security extends Modules\Addons\Entity
{
    public $url = 'security';
    public $name = 'Безопасность';
    public $description = '';
    public $is_backend = 1;
    public $is_core = 1;
    public $is_menu = 1;
    public $group = 'manage';
    
    function versions()
    {
        return array(
            '0.2.85',
            '0.2.91'
        );
    }

    function update_to_0_2_91()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('security_logs') ." ADD  `backend` TINYINT( 1 ) NOT NULL");
    }

    function update_to_0_2_85()
    {        
        $this->db->query("CREATE TABLE  ". $this->db->dbprefix('security_logs') ." (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `user_id` INT UNSIGNED NOT NULL ,
        `login` VARCHAR( 50 ) NOT NULL,
        `created_on` INT UNSIGNED NOT NULL ,
        `type` TINYINT( 1 ) NOT NULL ,
        `ip` VARCHAR( 45 ) NOT NULL ,
        `user_agent` VARCHAR( 255 ) NOT NULL
        ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;");
    }
}