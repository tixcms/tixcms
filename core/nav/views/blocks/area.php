<?php if( isset($links[$parent_id]) ):?>
    <ul>
        <?php foreach($links[$parent_id] as $item):?>
            <li class="<?=($item->url AND strpos($current_uri, $item->url) === 0) ? ' active' : ''?>">
                <?=URL::anchor(
                    $item->url,
                    $item->name
                )?>
                <?php if( isset($links[$item->id]) ):?>
                    <?=$this->template->view('nav::blocks/area', array(
                        'parent_id'=>$item->id
                    ))?>
                <?php endif?>
            </li>
        <?php endforeach?>
    </ul>
<?php endif?>