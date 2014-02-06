<?php if( $categories ):?>
    <ul>
    <?php foreach($categories as $cat):?>
        <li>
            <?=URL::anchor(
                $this->module .'/category/'. $cat->id,
                $cat->title
            )?>
        </li>
    <?php endforeach?>
    </ul>
<?php endif?>