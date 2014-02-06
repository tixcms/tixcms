<ul class="nav">
    <?php if( $groups ):?>
        <?php if( isset($modules_by_groups['no_group']) ):?>
            <?php foreach($modules_by_groups['no_group'] as $item ):?>
                <li<?=$this->module->url == $item->url() ? ' class="active"' : ''?>>
                    <?=URL::anchor(
                        'admin/'. $item->url(),
                        $item->name(),
                        Notices::has($item->url()) ? array(
                            'style'=>'border-bottom: 1px solid #999;',
                            'data-title'=>Notices::get_label($item->url()) .' ('. Notices::get_count($item->url()) .')',
                            'rel'=>'tooltip',
                            'data-placement'=>'bottom'
                        ) : ''
                    )?>
                </li>
            <?php endforeach?>
        <?php unset($modules_by_groups['no_group']); endif?>
    
        <?php foreach($groups as $group):?>
            <?php if( isset($modules_by_groups[$group->alias]) ):?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <?=$group->name?>
                    <b class="caret"></b></a>
                <ul class="dropdown-menu">
                <?php foreach($modules_by_groups[$group->alias] as $item ):?>
                    <li<?=$this->module->url == $item->url() ? ' class="active"' : ''?>>
                        <?=URL::anchor(
                            'admin/'. $item->url(),
                            $item->name() . 
                            (
                                Notices::has($item->url()) 
                                    ? '<span class="pull-right badge badge-important">'. Notices::get_count($item->url()) .'</span>' 
                                    : ''
                            )
                        )?>
                    </li>
                <?php endforeach?>
                </ul>
                </li>
            <?php endif?>
        <?php endforeach?>
    <?php endif?>
</ul>