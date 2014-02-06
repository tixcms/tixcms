<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
</a>
<?=URL::anchor(
    'admin/dashboard',
    'TixCMS',
    array(
        'class'=>'brand'
    )
)?>
<?=URL::anchor(
    'admin/dashboard',
    lang('admin:dashboard'),
    array(
        'class'=>'brand'
    )
)?>
<div class="nav-collapse">
    
    <?=Block::view('Admin::Nav\Header')?>
    
    <ul class="nav pull-right">
        <li>
            <?=URL::anchor(
                '',
                lang('admin:back_to_site')
            )?>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?=$this->user->login?> <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <?=URL::anchor(
                        'admin/dashboard/profile',
                        lang('admin:profile')
                    )?>
                </li>
                <li>
                    <?=URL::anchor('admin/logout', lang('admin:logout'))?>
                </li>
            </ul>
        </li>
    </ul>
</div>