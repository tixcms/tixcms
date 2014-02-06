<?php

class Categories extends Modules\Addons\Entity
{    
    public $url ='categories';
    public $name = 'Категории';
    public $description = '';
    public $is_core = 1;
    
    function versions()
    {
        return array(
            '0.422', '0.45'
        );
    }
    
    function update_to_0_45()
    {
        Helpers\File::make_path(\Categories\Form::IMG_UPLOAD_PATH);
        
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('categories') ." ADD  `url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL AFTER  `id`");
    }
    
    function update_to_0_422()
    {
        $this->db->query("UPDATE  ". $this->db->dbprefix('modules') ." SET  `is_service` =  '1' WHERE  `url` = 'categories'");
    }
    
    function install()
    {        
        $this->db->query("CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('categories') ." (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `module` varchar(50) NOT NULL,
          `title` varchar(100) NOT NULL,
          `description` varchar(648) NOT NULL,
          `icon` varchar(50) NOT NULL,
          `items` INT UNSIGNED NOT NULL,
          `is_active` tinyint(1) unsigned NOT NULL,
          `lft` int(10) unsigned NOT NULL,
          `rgt` int(10) unsigned NOT NULL,
          `level` int(10) unsigned NOT NULL,
          `meta_title` VARCHAR( 255 ) NOT NULL,
          `meta_description` VARCHAR( 255 ) NOT NULL,
          `meta_keywords` VARCHAR( 255 ) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
    }
}