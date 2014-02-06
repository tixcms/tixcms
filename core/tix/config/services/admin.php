<?php

$config['services'] = array(
    'template'=>function(){
        return \CI::$APP->template;  
    },
    'events'=>'Tix\Events',
    'breadcrumbs'=>function(){
        return new \Tix\Breadcrumbs(array(
            'index_item'=>array('<i class="icon-home" style="margin-top: 7px;"></i>', 'admin/dashboard'),
            'delimiter'=>' » ',
            'item_before'=>'',
            'item_after'=>'',
            'start_tag'=>'<div class="page-header"><h3>',
            'end_tag'=>'</h3></div>',
            'show_on_index'=>FALSE
        ));
    },
    'pager'=>function(){
        $pager = new \Tix\Pagination(array(
            'view'=>'admin::pagination'
        ));

        return $pager;
    },
    'alert'=>function(){
        return new \Tix\Alert(array(
            'folder'=>\CI::$APP->input->is_ajax_request() ? 'admin::alerts/js/' : 'admin::alerts/'
        ));
    },
    'assets'=>'Tix\Assets',
    'seo'=>function(){
        return new \Tix\Seo(array(
            'add_method'=>'prepend'
        ));
    },
    'url'=>'Helpers\URL',
    'email'=>'Tix\Email',
    'date'=>'Tix\Date',
    'string'=>'Tix\String'
);