<?php if( $table->total AND $table->per_page_options ):?>
    <div class="btn-group dropup">
      <button class="btn"><?=$table->per_page?></button>
      <button class="btn dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <?php foreach($table->per_page_options as $option):?>
            <?php $page = $table->url_query->get('page')?>
            <?php $table->url_query->set('page', false)?>
            <?=$table->url_query->set('per_page', $option)?>
            <li <?=$table->per_page == $option ? ' class="active"' : ''?>>
                <a href="?<?=$table->url_query->generateUriQuery()?>">
                    <?=$option?>
                </a>
            </li>
        <?php endforeach?>
        <?=$table->url_query->set('per_page', $table->per_page)?>
        <?php $table->url_query->set('page', $page)?>
      </ul>
    </div>
<?php endif?>