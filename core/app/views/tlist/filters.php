<?php if( $list->filters ):?>

        <?php foreach( $list->filters as $key=>$filter ):?>
            
            <strong>
                <?=$filter['label']?>: 
            </strong>
            
            <?php foreach($filter['options'] as $id=>$val):?>
                <?=URL::anchor(
                    $list->current_url,
                    $val,
                    array(
                        'style'=>'margin: 0 5px;'. ( $list->url_query->get($key) == $id ? ' font-weight: bold;' : '' ),
                        'rel'=>'nofollow'
                    ),
                    '?'. $list->url_query->generate($key, $id, array('page', 'sort'))
                )?>
            <?php endforeach?>
            
            <br />
            
        <?php endforeach?>
    </form>
<?php endif?>