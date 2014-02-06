<?php if( $this->settings->theme_sidebar == 'left' ):?>
    <div class="span3">
        <?=Block::area('sidebar')?>
    </div>
<?php endif?>

<div class="span9">
    <div class="well">
        <?=$this->di->breadcrumbs->render()?>
    
        <?=$content?>
    </div>
    
    <?=Block::area('under-content', '::blocks/bottom/area')?>
</div>

<?php if( $this->settings->theme_sidebar == 'right' ):?>
    <div class="span3">
        <?=Block::area('sidebar')?>
    </div>
<?php endif?>