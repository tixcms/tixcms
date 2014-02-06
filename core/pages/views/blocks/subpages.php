<?php if( $subpages ):?>
    <ul style="list-style: none;">        
        <?php foreach($subpages as $subpage):?>
            <li<?=$subpage->id == $page->id ? ' class="active"' : ''?>>
                <?=URL::anchor(
                    $subpage->full_url,
                    $subpage->title
                )?>
            </li>
        <?php endforeach?>
    </ul>
<?php endif?>