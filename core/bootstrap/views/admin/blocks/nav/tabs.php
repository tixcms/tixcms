<ul class="nav nav-tabs">
    <?php foreach($items as $item):?>
        <li<?=(isset($item['active']) AND $item['active']) ? ' class="active"' : ''?>>
            <?=URL::anchor(
                $item['url'],
                $item['label']
            )?>
        </li>
    <?php endforeach?>
</ul>