<?php if( $pages ):?>
    <?php foreach($pages as $page):?>
        <li>
            <?=URL::anchor(
                $page->url,
                $page->title
            )?>
        </li>
    <?php endforeach?>
<?php endif?>