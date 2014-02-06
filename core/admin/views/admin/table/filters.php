<?php if( $table->filters ):?> 

    <?php foreach($table->filters as $field=>$filter):?>
    
        <?php $type = isset($filter['type']) ? $filter['type'] : 'dropdown'?>
        <?php $view = isset($filter['view']) ? $filter['view'] : $table->view_filters_items . $type?>
    
        <?=$this->template->view($view, array(
            'field'=>$field,
            'table'=>$table,
            'filter'=>$filter
        ))?>
        
    <?php endforeach?>
    
    <?php foreach($table->filters as $field=>$filter):?>
        <?php $table->url_query->set($field, false)?>
    <?php endforeach?>
    
    <a href="<?=URL::current_url()?>" class="reset-filter btn-small btn">
        сбросить
    </a>
    
<?php endif?>   