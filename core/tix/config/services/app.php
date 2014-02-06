<?php

$config['services'] = array(
    'template'=>function(){
        return \CI::$APP->template;  
    },
    'url'=>function(){
        return new \Helpers\URL;
    },
    'assets'=>'Tix\Assets',
    'events'=>'Tix\Events',
    'email'=>function(){
        return new \Tix\Email(array(
            'wordwrap'=>false
        ));
    },
    'tag'=>'HTML\Tag',
    'breadcrumbs'=>function(){         
        return new \Tix\Breadcrumbs;
    },
    'pager'=>function(){
        $pager = new \Tix\Pagination(array(
            'view'=>'app::pagination'
        ));

        return $pager;
    },
    'alert'=>function(){
        return new \Tix\Alert(array(
            'folder'=>'app::alerts/'
        ));
    },
    'seo'=>function(){
        return new \Tix\Seo(array(
            'add_method'=>'prepend',
            'default'=>array(
                'title'=>CI::$APP->settings->site_name,
                'description'=>CI::$APP->settings->site_description,
                'keywords'=>CI::$APP->settings->site_keywords
            )
        ));
    },
    'date'=>'Tix\Date',
    'string'=>'Tix\String',
    'purifier'=>'Purifier'
);