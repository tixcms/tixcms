<hr style="margin-bottom: 10px;" /> 
<div class="container-fluid">
    <div class="row-fluid">
        <div class="<?=$this->user->settings('dashboard_left_sidebar') ? 'span12' : 'span8 offset2'?>">
            <div class="footer-wrap">
                <?php /*
                <p class="pull-right">
                    <?=URL::anchor(
                        'admin/settings/lang/ru',
                        'RU'
                    )?>
                    <?=URL::anchor(
                        'admin/settings/lang/en',
                        'EN'
                    )?>
                </p>
                */?>
                <p>
                    Copyright &copy;
                    2012<?=date('Y') > 2012 ? ' - '. date('Y') : ''?>
                    <?=URL::anchor('http://tixcms.ru', 'TixCMS')?>
                    <?=lang('admin:version')?> <?=$core_version?>
                </p>
            </div>
        </div>
    </div>
</div>