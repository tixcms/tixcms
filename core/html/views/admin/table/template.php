<?php if( $items ):?>
    <?php $attrs['class'] = 'table table-bordered' . (( isset($attrs) AND isset($attrs['class']) ) 
                                ? ' '. $attrs['class']
                                : '' )?>
     <table <?=HTML\Tag::parse_attributes($attrs)?>>
    	<thead>
    		<tr class="nodrag nodrop">
                <?php foreach($headings as $item):?>
                    <th style="text-align: center;">
                        <?php echo $item?>
                    </th>
                <?php endforeach;?>
    		</tr>
    	</thead>
    	<tbody>
            <?php if( $items ):?>
                <?php HTML\TList::render(array(
                    'view'=>$view,
                    'items'=>$items
                )) ?>
            <?php else:?>
            
            <?php endif;?>
        </tbody>
    </table>
    
    <?=isset($pagination) ? $pagination->render() : ''?>
<?php else:?>
    <?=$no_items?>
<?php endif;?>