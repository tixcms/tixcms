<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Панель управления</title>

<script>
    var BASE_URL = '<?=URL::base_url() . ($this->config->item('index_page') ? $this->config->item('index_page') .'/' : '')?>';
</script>

<?=jQuery::render('cdn')?>
<?=Bootstrap::all(TRUE)?>

<link rel="stylesheet" href="<?=URL::base_url() .'core/'. $this->module .'/css/style.css' ?>" />

<script src="<?=URL::base_url() .'core/'. $this->module .'/js/scripts.js' ?>"></script>

<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->