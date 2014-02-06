<?php if( ENVIRONMENT == 'development' ):?>
    <div class="pull-right">
        <?=$this->url->anchor(
            'admin/dashboard/adjust',
            'Настроить',
            array(
                'class'=>'btn'
            )
        )?>
    </div>
<?php endif?>

<?php if( $groups ):?>
    <?php if( isset($modules_by_groups['no_group']) ):?>
        <div class="row-fluid">
            <ul class="thumbnails" style="margin-bottom: 10px;">
                <?php foreach($modules_by_groups['no_group'] as $item):?>
                
                    <li class="span1" style="position: relative;">
                        <?php if( Notices::has($item->url) ):?>
                            <span 
                                class="badge" 
                                style="position: absolute; top: -10px; right: -10px;" 
                                rel="tooltip"
                                data-title="<?=Notices::get_label($item->url)?>"
                            >
                                <?=Notices::get_count($item->url)?>
                            </span>
                        <?php endif?>
                        <?=URL::anchor(
                            'admin/'. $item->url,
                            $this->di->assets->img($item->url .'::icon.png', array(
                                'style'=>'width: 50px; height: 50px;'
                            )),
                            array(
                                'class'=>'thumbnail',
                                'rel'=>'tooltip',
                                'data-title'=>$item->name,
                                'data-placement'=>'bottom',
                                //'style'=>'height: 89px;'
                            )
                        )?>
                    </li> 
                <?php endforeach?>
            </ul>
        </div>
    <?php unset($modules_by_groups['no_group']); endif?>

    <?php foreach($groups as $group):?>  
        <?php if( isset($modules_by_groups[$group->alias]) ):?>    
            <h3>
                <?=$group->name?>
            </h3>
            
            <div class="row-fluid">
                <ul class="thumbnails" style="margin-bottom: 10px;">
                    <?php foreach($modules_by_groups[$group->alias] as $item):?>
                        <li class="span1" style="position: relative;">
                            <?php if( Notices::has($item->url) ):?>
                                <span 
                                    class="badge" 
                                    style="position: absolute; top: -10px; right: -10px;" 
                                    rel="tooltip"
                                    data-title="<?=Notices::get_label($item->url)?>"
                                >
                                    <?=Notices::get_count($item->url)?>
                                </span>
                            <?php endif?>
                            <?=URL::anchor(
                                'admin/'. $item->url,
                                $this->di->assets->img($item->url .'::icon.png', array(
                                    'style'=>'width: 50px; height: 50px;'
                                )),
                                array(
                                    'class'=>'thumbnail',
                                    'rel'=>'tooltip',
                                    'data-title'=>$item->name,
                                    'data-placement'=>'bottom',
                                    //'style'=>'height: 89px;'
                                )
                            )?>
                        </li> 
                    <?php endforeach?>
                </ul>
            </div>
        <?php endif?>
    <?php endforeach?>  
<?php endif?>