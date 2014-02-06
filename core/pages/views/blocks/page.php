<!-- about-block -->
<div class="about-block">
	<h3 class="about-block_title">
		<?=$block->title?>
	</h3>

	<div class="about-block_text">
		<?=nl2br($pageEntity->preview)?>
	</div>
    
	<div class="about-block_more">
        <?=$this->url->anchor(
            $pageEntity->full_url(),
            '<span>Подробнее</span>'
        )?>
	</div>
</div>