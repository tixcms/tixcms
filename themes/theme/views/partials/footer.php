<div class="container">
    <div class="row footer-section-1">
        <div class="span6">
            <?=URL::anchor('', $this->settings->site_name)?> <?=date('Y')?>
        </div>
        
        <div class="span6">
            <ul class="nav nav-pills pull-right" style="margin-bottom: 0; margin-top: 0;">
                 <?=Block::view('Nav::Area', array(
                    'area'=>'footer'
                 ))?>
            </ul>
        </div>
    </div>
</div>

<?=Block::area('bottom', false, false)?>