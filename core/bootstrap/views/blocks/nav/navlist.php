<ul class="nav nav-list">
    <?php foreach($items as $item):?>
        
        <?php if( is_string($item) ):?>
            <li class="nav-header">
                <?=$item?>
            </li>
        <?php else:?>
            <li<?=$item['active'] ? ' class="active"' : ''?>>
                <?=URL::anchor(
                    $item['url'],
                    $item['label']
                )?>
            </li>
        <?php endif?>
        
    <?php endforeach?>
</ul>