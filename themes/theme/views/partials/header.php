<div class="span3 header-logo">
    <a href="<?=$this->url->site_url()?>">
        <?=$this->settings->theme_logo 
            ? HTML\Tag::img($this->url->site_url( \Theme\Settings::UPLOAD_PATH . $this->settings->theme_logo, false))
            : $this->assets->img('::logo.png') ?>
    </a>
</div>
<div class="span9">
    <ul class="nav header-nav" style="text-align: right; padding-top: 10px; padding-right: 10px;">
        
        <?=Block::get('Nav::Area', array(
            'area'=>'header'
        ))?>
        
    </ul>
</div>