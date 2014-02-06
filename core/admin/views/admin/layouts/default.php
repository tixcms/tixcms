<!DOCTYPE html>
<html lang="en">
<head>
    <?=$this->template->view('admin::partials/metadata')?>
</head>
<body>

<div class="wrap">
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="<?=$this->user->settings('dashboard_left_sidebar') ? 'span12' : 'span8
offset2'?>">
                        <?=$this->template->view('admin::partials/header')?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" style="padding-top: 60px; padding-bottom: 70px;">
        <div class="row-fluid content">
            <?php if( (isset($this->user->settings['dashboard_left_sidebar'])
                AND $this->user->settings['dashboard_left_sidebar']) ):?>
                <div class="span2 sidebar">
                    <div class="affix span2">
                        <?=$this->template->view('admin::partials/sidebar')?>
                    </div>
                    
                </div>
            <?php endif?>
                
            <div class="<?=$this->user->settings('dashboard_left_sidebar') ? 'span10' : 'span8  offset2'?>
            content-main">
                <?=$this->di->alert->render()?>
            
                <?=$content?>
            </div>
        </div>           
    </div>
    <div id="push"></div>
</div>
    
<footer>
    <?=$this->template->view('admin::partials/footer')?>
</footer>

</body>
</html>