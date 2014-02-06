<form <?=$form->render('attrs')?>>

    <?php if( $form->actions_position == \Dashboard\Settings::FORM_ACTIONS_POSITION_TOP
    OR $form->actions_position == \Dashboard\Settings::FORM_ACTIONS_POSITION_BOTH ):?>

        <div class="page-header">
            <div class="header-actions">
                <?=$form->render('actions')?>
            </div>
        </div>

    <?php endif?>
    
    <?php if( $form->module == 'settings' ):?>
        <div class="row-fluid">
            <div class="span<?=(isset($this->user->settings['dashboard_left_sidebar'])
                AND $this->user->settings['dashboard_left_sidebar']) ? 2 : 3?>">
                <div class="well" style="padding: 8px 0;">
                    <ul class="nav nav-list settings-list nav-stacked">
                        <li class="active">
                            <a href="#settings" data-toggle="tab">
                                Общие
                            </a>
                        </li>
                    
                        <?php foreach($form->modules as $module):?>
                            <li>
                                <a href="#<?=$module->url?>" data-toggle="tab">
                                    <?=$module->url == 'settings' ? 'Общие' : $module->name?>
                                </a>
                            </li>
                        <?php endforeach?>
                    </ul>
                </div>
            </div>
            <div class="span<?=(isset($this->user->settings['dashboard_left_sidebar'])
                AND $this->user->settings['dashboard_left_sidebar']) ? 10 : 9?>">
                <div class="tab-content">
                    <div class="tab-pane active" id="settings">
                        <?=$form->render('inputs')?>
                    </div>
                
                    <?php foreach($form->modules as $module):?>
                        <div class="tab-pane" id="<?=$module->url?>">
                            <?php if( $module->form->tabs ):?>
                                <ul class="nav nav-tabs">
                                <?php $i=0; foreach($module->form->tabs as $key=>$tab):?>
                                    <li<?=$i==0? ' class="active"' : ''?>>
                                        <a data-toggle="tab" href="#<?=$key?>">
                                            <?=$tab?>
                                        </a>
                                    </li>
                                <?php $i++; endforeach;?>
                                </ul>
                            <?php endif;?>
                        
                            <?php $module->form->before_render()?>
                            <?php $i=0; if( $module->form->tabs ):?>
                                <div class="tab-content">
                                    <?php foreach($module->form->tabs as $key=>$tab):?>
                                        <div class="tab-pane<?=$i==0 ? ' active' : ''?>" id="<?=$key?>">
                                            <?=$module->form->render(
                                                'inputs', 
                                                $module->form->settings_by_tabs[$key]
                                            )?>
                                        </div>
                                    <?php $i++; endforeach;?>
                                </div>
                            <?php else:?>
                                <?=$module->form->render('inputs')?>
                            <?php endif?>                            
                        </div>
                    <?php endforeach?>
                </div>
            </div>
        </div>
    <?php else:?>
        <?php if( $form->tabs ):?>
            <ul class="nav nav-tabs">
            <?php $i=0; foreach($form->tabs as $key=>$tab):?>
                <li<?=$i==0? ' class="active"' : ''?>>
                    <a data-toggle="tab" href="#<?=$key?>">
                        <?=$tab?>
                    </a>
                </li>
            <?php $i++; endforeach;?>
            </ul>
        <?php endif;?>
        
        <div class="row-fluid">
            <?php $i=0; if( $form->tabs ):?>
                <div class="tab-content">
                    <?php foreach($form->tabs as $key=>$tab):?>
                        <div class="tab-pane<?=$i==0 ? ' active' : ''?>" id="<?=$key?>">
                            <?=$form->render('inputs', $form->settings_by_tabs[$key])?>
                        </div>
                    <?php $i++; endforeach;?>
                </div>
            <?php else:?>
                <?=$form->render('inputs')?>
            <?php endif?>
        </div>
    <?php endif?>

    <?php if( $form->actions_position == \Dashboard\Settings::FORM_ACTIONS_POSITION_BOTTOM
        OR $form->actions_position == \Dashboard\Settings::FORM_ACTIONS_POSITION_BOTH ):?>

        <div class="form-actions">
            <?=$form->render('actions')?>
        </div>

    <?php endif?>
</form>

<div class="modal hide form-help">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h3>Справка по полям формы</h3>
  </div>
  <div class="modal-body">
    <?=$form->render('help')?>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Закрыть</a>
  </div>
</div>