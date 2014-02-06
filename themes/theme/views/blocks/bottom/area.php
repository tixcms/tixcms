<div class="row-fluid">
    <?php $i=1; foreach($blocks as $block):?>
        <div class="span6">
            <?=$block?>
        </div>
        
        <?=$i%2 == 0 ? '</div><div class="row-fluid">' : ''?>
    <?php $i++; endforeach?>
</div>