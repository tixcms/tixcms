<tr>
    <?php foreach($table->headings as $key=>$attrs):?>                
        <th <?=HTML\Tag::parse_attributes($attrs['attrs'])?>>
            <?php if( $attrs['sortable'] ):?>
            
                <?=URL::anchor(
                    URL::current_url(),
                    $attrs['label'],
                    '',
                    '?' . $table->url_query->generateUriQuery(
                        'sort', 
                        $key . ($table->url_query->get('sort') == $key .'_asc' ? '_desc' : '_asc'),
                        array('page')
                    )
                )?>
                
                <?php if( strstr($table->url_query->get('sort'), $key) !== FALSE ):?>
                    <?=$table->url_query->get('sort') == $key .'_asc' ? '&darr;' : '&uarr;'?>
                <?php endif?>
                
            <?php else:?>
                <?=$attrs['label']?>
            <?php endif?>
        </th>
    <?php endforeach;?>
</tr>