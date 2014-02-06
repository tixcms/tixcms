<?php

class Pages extends Modules\Addons\Entity
{    
    public $url = 'pages';
    public $name = 'Страницы';
    public $description = 'Страницы сайта';
    public $is_frontend = 1;
    public $is_backend = 1;
    public $is_core = 1;
    public $is_menu = 1;
    
    function versions()
    {
        return array('0.4', '0.424', '0.432', '0.443', '0.47');
    }
    
    function update_to_0_47()
    {
        $this->db->query("UPDATE  ". $this->db->dbprefix('modules') ." SET  `is_frontend` =  '1' WHERE  `url` = 'pages'");
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('pages') ." ADD  `created_on` INT UNSIGNED NOT NULL ,
ADD  `updated_on` INT UNSIGNED NOT NULL");
        $this->db->query("UPDATE  ". $this->db->dbprefix('pages') ." SET created_on = UNIX_TIMESTAMP( NOW( ) ) ,
updated_on = UNIX_TIMESTAMP( NOW( ) ) WHERE 1");
    }
    
    function update_to_0_443()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('pages') ." ADD  `view` VARCHAR( 50 ) NOT NULL AFTER  `body`");
    }
    
    function update_to_0_432()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('pages') ." ADD  `access` VARCHAR( 255 ) NOT NULL");
    }
    
    function update_to_0_424()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('pages') ." ADD  `is_main` TINYINT( 1 ) UNSIGNED NOT NULL AFTER  `meta_keywords`");
        
        $this->load->model('pages/pages_m');
        
        $main = $this->pages_m->by_level(0)->get_one();
        
        $this->pages_m->insert(array(
            'module'=>$main->module,
            'title'=>$main->title,
            'body'=>$main->body,
            'is_active'=>$main->is_active,
            'is_main'=>1,
            'url'=>$main->url,
            'pre_url'=>$main->pre_url,
            'meta_title'=>$main->meta_title,
            'meta_keywords'=>$main->meta_keywords,
            'meta_description'=>$main->meta_description
        ), $main->id);
    }
    
    function update_to_0_4()
    {
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('pages') ." ADD  `module` VARCHAR( 50 ) NOT NULL AFTER  `id`");
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('pages') ." CHANGE  `url`  `url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL");
        $this->db->query("ALTER TABLE  ". $this->db->dbprefix('pages') ." CHANGE  `pre_url`  `pre_url` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL");
        $this->db->query("UPDATE ". $this->db->dbprefix('pages') ." SET is_active = 1 WHERE level = 0 ");
    }
    
    function install()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS ". $this->db->dbprefix('pages') ." (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,          
          `url` varchar(255) NOT NULL,
          `pre_url` VARCHAR( 500 ) NOT NULL,
          `title` varchar(255) NOT NULL,
          `body` mediumtext NOT NULL,
          `meta_title` varchar(255) NOT NULL,
          `meta_description` varchar(255) NOT NULL,
          `meta_keywords` varchar(255) NOT NULL,
          `is_active` tinyint(1) NOT NULL,
          `level` TINYINT UNSIGNED NOT NULL,
          `lft` INT UNSIGNED NOT NULL ,
           `rgt` INT UNSIGNED NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
        
        $this->db->query("INSERT INTO ". $this->db->dbprefix('pages') ." (`title`, `body`, `lft`, `rgt`, `level`, `is_active`) VALUES ('Главная страница', '<p>Установка выполнена успешно. Вы находитесь на главной странице сайта.</p>
<p>Для доступа в панель управления перейдите по этой <a href=\"/admin\">ссылке</a>.</p>
<p> </p>

<p>
Что можно сделать:
 </p>
<p> </p>
<p>
- Добавить ссылки в области навигации. В данной теме создано 5 областей навигации, вы можете добавить ссылки в разделе Навигация Панели управления
</p>
<p>
- Добавить блоки в боковую панель
</p>
<p>- Скачать необходимые дополнения с сайта <a href=\"http://tixcms.ru/addons/\" rel=\"nofollow\">TixCMS</a></p>

<p> </p>
<p>Если у вас есть вопросы или предложения, пишите на почту <strong>tixcms@gmail.com</strong>. Мы будем очень рады.</p>
<p> </p>
<p>Желаем Вам приятного использования системы.</p>
<p> </p>
<p>Мы также предлагаем услуги по созданию сайтов на TixCMS, созданию новых дополнений или адаптиции существующих под ваши требования, натяжка любых тем на систему.</p>

', 1, 2, 0, 1)");
    }
}