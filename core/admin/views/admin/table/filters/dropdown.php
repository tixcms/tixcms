<?php $current_value = $table->url_query->get($field)?>
    
<div class="btn-group">
  <button class="btn <?=($current_value AND $current_value != 'all') ? 'btn-warning' : ''?>">
    <?=$current_value
        ? $filter['options'][$table->url_query->get($field)]
        : array_shift(array_values($filter['options']))?>
  </button>
  <button class="btn dropdown-toggle" data-toggle="dropdown">
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <?php $page = $table->url_query->get('page')?>
    <?php $table->url_query->set('page', false)?>
    <?php $foo = $table->url_query->get($field)?>
    
    <?php $i=0; foreach($filter['options'] as $value=>$label):?>
        <?php $table->url_query->set($field, $value)?>
        <li<?=((!$current_value AND $i==0) OR $current_value == $value) ? ' class="active"' : ''?>>
            <a href="?<?=$table->url_query->generateUriQuery()?>">
                <?=$label?>
            </a>
        </li>
    <?php $i++; endforeach?>
    
    <?php $table->url_query->set($field, $foo)?>
    <?php $table->url_query->set('page', $page)?>
  </ul>
</div>