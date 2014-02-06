<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
</a>
<?=URL::anchor(
    'admin',
    'TixCMS - Установщик',
    array(
        'class'=>'brand'
    )
)?>

<?php if( $this->controller == 'init' ):?>
    <a class="brand">-</a> <a class="brand">Первоначальная установка</a>
<?php endif?>

<div class="nav-collapse">
    <ul class="nav">
    </ul>
    <ul class="nav pull-right">
    </ul>
</div>