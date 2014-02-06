<ul class="nav nav-tabs">
    <li>
        <?=URL::anchor(
            'admin/'. $this->module->url,
            '&larr; Обратно',
            array(
                'style'=>'float: left;'
            )
        )?>
    </li>
    <li class="active">
        <a href="#for-user" data-toggle="tab">Пользователю</a>
    </li>
    <?php if( $plugins ):?>
        <li>
            <a href="#plugins" data-toggle="tab">Плагины</a>
        </li>
    <?php endif?>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="for-user">
        <?php if( $has_user_help ):?>
            <?=$this->template->view('help/user')?>
        <?php endif?>
    </div>
    <?php if( $plugins ):?>
        <div class="tab-pane" id="plugins">
            <?=$this->template->view('admin::help/plugins')?>
        </div>
    <?php endif?>
</div>
