<?php if( $area == 'sidebar' ):?>

    <ul>
        <?php if( isset($links[$parent_id]) ):?>
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
        <?php endif?>
    </ul>
    
<?php elseif( $area == 'footer' ):?>

    <?php if( isset($links[$parent_id]) ):?>
        <?php foreach($links[$parent_id] as $item):?>
            <li class="<?=($item->url AND strpos($current_uri, $item->url) === 0) ? ' active' : ''?>">
                <?=URL::anchor(
                    $item->url,
                    $item->name
                )?>
            </li>
        <?php endforeach?>
    <?php endif?>
    
<?php else:?>

    <?php if( isset($links[$parent_id]) ):?>
        <?php foreach($links[$parent_id] as $item):?>
            <li 
                class="
                    <?=($item->url AND strpos($current_uri, $item->url) === 0) ? ' active' : ''?>
                    <?=isset($links[$item->id]) ? ' dropdown' : ''?>
                "
            
            >
                <?=URL::anchor(
                    $item->url,
                    $item->name . (isset($links[$item->id]) ? ' <b class="caret"></b>' : ''),
                    isset($links[$item->id]) 
                        ? array(
                            'class'=>"dropdown-toggle",
                            'data-toggle'=>"dropdown"
                        ) : ''
                )?>
                <?php if( isset($links[$item->id]) ):?>
                    <ul class="dropdown-menu">
                        <?=$this->template->view('nav::blocks/area', array(
                            'parent_id'=>$item->id
                        ))?>
                    </ul>
                <?php endif?>
            </li>
        <?php endforeach?>
    <?php endif?>
    
<?php endif?>