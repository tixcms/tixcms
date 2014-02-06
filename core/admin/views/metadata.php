<meta charset="utf-8" />
<title>Вход в Панель управления <?=$this->settings->site_name?></title>


<?=$this->di->assets->render_css('admin::login.css')?>

<?=jQuery::render('cdn')?>
<?=Bootstrap::all()?>

<?=$this->di->assets->all()?>

<style type="text/css">
    body {
        padding-top: 60px;
        padding-bottom: 40px;
    }
</style>

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->