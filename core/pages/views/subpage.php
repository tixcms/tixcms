<div class="page-header">
    <h2>
        <?=$page->title?>
    </h2>
</div>

<?php if( !$page->module AND $page->level != 0 ):?>
    <?=Block::get('Pages::Subpages', array(
        'page'=>$page,
        'first_parent'=>$first_parent
    ))?>
<?php endif?>

<div class="page-text">
    <?=$page->body?>
</div>