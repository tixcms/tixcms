<?php if( $table->items ):?>
    <table <?=HTML\Tag::parse_attributes($table->attrs)?>>
        <?php if( $table->headings ):?>
            <thead>
                <?=$table->render_head()?>
            </thead>
        <?php endif?>
        <tbody>
            <?=$table->render_items()?>
        </tbody>
    </table>

    <?=$table->render_pager()?>
<?php else:?>
    <?=$table->no_items?>
<?php endif?>