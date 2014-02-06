<ul class="nav">
    <?php if( $nav ):?>
        <?php foreach($nav as $item):?>
            <?php if( $this->user->module_access($item->url)):?>
                <li<?=$this->module->url == $item->url ? ' class="active"' : ''?>>
                    <?=URL::anchor(
                        'admin/'. $item->url,
                        $item->name,
                        (isset($modules_new[$item->url]) AND $modules_new[$item->url]['count'] > 0)
                        ? array(
                                'style'=>'border-bottom: 2px solid #777',
                                'rel'=>'tooltip',
                                'data-title'=>$modules_new[$item->url]['title'],
                                'data-placement'=>'bottom'
                            )
                        : ''
                    )?>
                </li>
            <?php endif?>
        <?php endforeach?> 
    <?php endif?>
    
    <?php if( $more_nav ):?>
        <li class="dropdown">
            <a class="dropdown-toggle"
               data-toggle="dropdown"
               href="#">
                Другие
                <b class="caret"></b>
              </a>
            <ul class="dropdown-menu">
                <?php foreach($more_nav as $item):?>
                    <li<?=$this->module->url == $item->url ? ' class="active"' : ''?>>
                        <?=URL::anchor(
                            'admin/'. $item->url,
                            $item->name
                        )?>
                    </li>
                <?php endforeach?>
            </ul>
          </li>
    <?php endif?>
</ul>