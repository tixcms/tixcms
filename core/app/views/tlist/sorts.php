<?php if( $list->sorts ):?>

    <?php foreach( $list->sorts as $key=>$data ):?>
        <span>
            <?=URL::anchor(
                URL::current_url(),
                $data['label'],
                '',
                '?' . $list->url_query->generateUriQuery(
                    'sort',
                    $key . ($list->url_query->get('sort') == $key .'_asc' ? '_desc' : '_asc'),
                    array('page')
                )
            )?>
    
            <?php if( strstr($list->url_query->get('sort'), $key) !== false ):?>
            
                <?=$list->url_query->get('sort') == $key .'_asc' ? '&darr;' : '&uarr;'?>
                
            <?php endif?>
        </span>
    
    <?php endforeach;?>
    
<?php endif?>