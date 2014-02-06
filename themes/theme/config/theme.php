<?php

// вложенные шаблоны
$config['layout'] = array('::layouts/default', '::layouts/sidebar');
$config['breadcrumbs'] = array(
    'index_item'=>array('<i class="icon-home"></i>', ''),
    'delimiter'=>' » ',
    'item_before'=>'<li>',
    'item_after'=>'</li>',
    'start_tag'=>'<ul class="breadcrumb">',
    'end_tag'=>'</ul>',
    'show_on_index'=>false
);