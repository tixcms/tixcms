<?php

$config = array(
    'wysiwyg_instance' => false,

    'template_engines'=>array(
        'default'=>new \Tix\Template\Native
    ),

    'services'=>array(
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
        'assets'=>'Assets',
        'seo'=>function(){
            return new \Tix\Seo(array(
                'add_method'=>'prepend'
            ));
        },
        'url'=>function(){
            return new \Helpers\URL;
        },
        'email'=>function(){
            return new \Tix\Email;
        }
    ),
    'helpers'=>array('language')
);