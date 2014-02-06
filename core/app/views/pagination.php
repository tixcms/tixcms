<?php if($pages_total > 1):?>
    <div class="pagination">
    <ul>
        <li class="prev<?=$current_page == 1 ? ' disabled' : ''?>">
            <?=$current_page != 1 
                ? $pager->url(1, '««', 'title="Первая страница"') 
                : '<a>««</a>'?>
        </li>

    
        <li<?=$current_page == 1 ? ' class="disabled"' : ''?>>
            <?php echo $current_page != 1 
                ? $pager->url($current_page - 1, '«')
                : '<a>«</a>'?>
        </li>

    
        <?php if($current_page <= 10):?>

            <?php if($pages_total <= 10):?>

                <?php for($i=1; $i<=$pages_total; $i++):?>
                    <li<?=($current_page == $i ? ' class="active"': '')?>>
                        <?=($current_page != $i) 
                            ? $pager->url($i, $i)
                            : '<a>'. $i .'</a>'?>
                    </li>
                <?php endfor;?>

            <?php else:?>

                <?php for($i=1; $i<=10; $i++):?>
                    <li<?=($current_page == $i ? ' class="active"': '')?>>
                        <?=($current_page != $i) 
                            ? $pager->url($i, $i, ($current_page == $i ? 'class="active"': '')) 
                            : '<a>'. $i .'</a>'?>
                    </li>
                <?php endfor;?>
                <li><?=$pager->url(11, '...')?></li>

            <?php endif;?>

        <?php else:?>
            <?php

            $start_page = ($current_page%10 != 0) ? floor($current_page/10)*10 + 1 : $current_page - 9;
            $end_page = $start_page + 9;

            if($pages_total < $end_page)
            {
                $end_page = $pages_total;
            }

            ?>
            <li><?=$pager->url($start_page - 1, '...')?></li>

            <?php for($i=$start_page; $i<=$end_page; $i++):?>
                <li<?=($current_page == $i ? ' class="active"': '')?>>
                    <?=($current_page != $i) 
                        ? $pager->url($i, $i)
                        : '<a>'. $i .'</a>'?>
                </li>
            <?php endfor;?>

            <?php if($pages_total > ($start_page + 10)):?>
                <li><?=$pager->url($end_page + 1, '...')?></li>
            <?php endif;?>

        <?php endif;?>
        
        <li<?=$current_page == $pages_total ? ' class="disabled"' : ''?>>
            <?=$current_page != $pages_total 
                ? $pager->url($current_page + 1, '»')
                : '<a>»</a>'?>
        </li>

        <li class="next<?=$current_page == $pages_total ? ' disabled' : ''?>">
            <?=$current_page != $pages_total 
                ? $pager->url($pages_total, '»»', 'title="Последняя страница"')
                : '<a>»»</a>'?>
        </li>
    </ul>
    </div>
<?php endif;?>
