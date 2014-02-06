<!DOCTYPE html>
<html>
<head>
    <title><?=$this->seo->site_title()?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
    <!-- Bootstrap -->
    <?=$this->assets->render_css('::bootstrap.min.css')?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    
    <script src="https://code.jquery.com/jquery.js"></script>
    <?=$this->assets->render_js('::bootstrap.min.js')?>
    
    <?=$this->assets->all()?>
</head>
<body>
    <h1>Hello, world!</h1>
</body>
</html>