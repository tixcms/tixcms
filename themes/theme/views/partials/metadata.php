<base href="<?=URL::base_url()?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$this->di->seo->site_title()?></title>
<?=$this->di->seo->description()?>
<?=$this->di->seo->keywords()?>

<script>
    var BASE_URL = '<?=URL::site_url() . ($this->config->item('index_page') ? '/' : '')?>';
    var CURRENT_URL = '<?=URL::current_url() . ($_SERVER['QUERY_STRING'] ? '?'. $_SERVER['QUERY_STRING'] : '')?>';
    var URI = {
        'module': '<?=$this->module->url?>',
        'controller': '<?=$this->controller?>',
        'action': '<?=$this->action?>'
    };
</script>

<?=jQuery::render('cdn')?>
<?=Bootstrap::all()?>
<?=$this->di->assets->css('bootstrap::font-awesome/css/font-awesome.min.css')?>
<?=$this->di->assets->css('::main.css')?>
<!--[if lt IE 9]>
  <?=$this->di->assets->css('::ie.css')?>
<![endif]-->

<?=$this->di->assets->all()?>

<style>
    .wrap {
        background: url(<?=URL::site_url('themes/'. $this->template->theme .'/img/bg/'. $this->settings->theme_bg .'.png')?>);
    }
    body, .footer {
        background: url(<?=URL::site_url('themes/'. $this->template->theme .'/img/bg/footer/'. $this->settings->theme_bg_footer .'.png')?>);
    }
</style>