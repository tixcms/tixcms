<form <?=$form->render('attrs')?>>

    <?php if( $form->actions_position == \Dashboard\Settings::FORM_ACTIONS_POSITION_TOP
        OR $form->actions_position == \Dashboard\Settings::FORM_ACTIONS_POSITION_BOTH ):?>

        <div class="row-fluid">
            <div class="span12">
                <div class="page-header">
                    <div style="margin-bottom: 5px;">
                        <?=$form->render('actions')?>
                    </div>
                </div>
            </div>
        </div>

    <?php endif?>

    <?=$form->legend ? '<legend>'. $form->legend .'</legend>' : ''?>
    
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
        <?php if( $form->tabs ):?>
            <div class="tab-content">
                <?php $i=0; foreach($form->tabs as $key=>$tab):?>
                    <div class="tab-pane<?=$i==0 ? ' active' : ''?>" id="<?=$key?>">
                        <?=$form->render('inputs', $form->settings_by_tabs[$key])?>
                    </div>
                <?php $i++; endforeach;?>
            </div>
        <?php else:?>
            <?=$form->render('inputs')?>
        <?php endif?>


    </div>

    <?php if( $form->actions_position == \Dashboard\Settings::FORM_ACTIONS_POSITION_BOTTOM
        OR $form->actions_position == \Dashboard\Settings::FORM_ACTIONS_POSITION_BOTH ):?>

            <div class="form-actions">
                    <?=$form->render('actions')?>
            </div>

    <?php endif?>
</form>