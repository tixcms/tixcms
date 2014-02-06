<div class="row-fluid">
    <div class="span8">
        <?php if( $form ):?>
            <?=$form->render()?>
        <?php else:?>
            <div class="page-header">
                <div class="header-actions">
                    <?=\URL::anchor('admin/block', lang('back'), array('class'=>'btn'))?>
                </div>
            </div>
        
            <?=$this->di->alert->message('info', 'Выберите блок в меню справа')?>
        <?php endif?>
    </div>
    <div class="span4">
        <?php if( $block ):?>
            <div class="well">
                <div class="page-header">
                    <h3><?=$block['name']?></h3>
                </div>
                
                <p><?=$block['description']?></p>
            </div>
        <?php endif?>
        
        <ul class="nav nav-tabs nav-stacked blocks-items" style="background: #fff;">
            <?=$blocks_list->render()?>
        </ul>
    </div>
</div>