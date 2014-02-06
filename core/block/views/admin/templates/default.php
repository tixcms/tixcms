<h3><?=$block_inst->title?></h3>

<div>
    <?=Block::view($block->class, unserialize($block_inst->data))?>
</div>