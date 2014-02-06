<?php if( $items ):?>
    <table class="table table-bordered">
    	<thead>
    		<tr>
                <?php foreach($headings as $item):?>
                    <th><?php echo $item?></th>
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
    
    <?=isset($pagination) ? $pagination->display() : ''?>
<?php else:?>
    <?=$no_items?>
<?php endif;?>