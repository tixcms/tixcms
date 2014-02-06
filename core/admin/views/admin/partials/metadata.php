<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Панель управления</title>
<script>
    var BASE_URL = '<?=URL::site_url()?>';
    var CURRENT_URL = '<?=URL::current_url() . ($_SERVER['QUERY_STRING'] ? '?'. $_SERVER['QUERY_STRING'] : '')?>';
    var URI = {
        'module': '<?=$this->module->url?>',
        'controller': '<?=$this->controller?>',
        'action': '<?=$this->action?>'
    };
</script>
<?=$this->di->assets->render_js('jquery::jquery-1.9.1.min.js')?>

<?=Bootstrap::all(true)?>
<?=$this->di->assets->render_css('bootstrap::font-awesome-4.0.3/css/font-awesome.min.css')?>
<?=$this->di->assets->render_css('admin::style.css', '', '3')?>

<?=$this->di->assets->render_js('jquery::plugins/jquery.autoresize.js')?>
<?=jQuery::plugin('notify')?>
<?=$this->di->assets->render_js('admin::admin.js', '', '3')?>

<?=$this->di->assets->all()?>

<?=WYSIWYG::init($this->config->item('wysiwyg_active'))?>

<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->