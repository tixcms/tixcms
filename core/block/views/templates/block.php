<div class="block well">
    <?php if( $block->show_title ):?>
        <h4 class="block-header"><?=$block->title?></h4>
    <?php endif?>
        
    <div>
        <?=Block::get($block->block_class, unserialize($block->data))?>
    </div>
</div>