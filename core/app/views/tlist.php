<div>
    <?=$list->render_sorts()?>
    
    <?=$list->render_filters()?>
    
    <?php if( $list->search ):?>
        <form action="" style="margin-bottom: 10px;" class="form-inline pull-right">
            <input type="text" value="<?=$list->url_query->get('search')?>" name="search" />
            <input type="submit" name="submit" value="поиск" class="btn" />
            <?=URL::anchor(
                $this->module->url,
                'сбросить',
                array(
                    'class'=>'btn'
                )
            )?>
        </form>
    <?php endif?>
</div> 

<div style="clear: both;"></div>
    
<div>
    <?=$list->render_items()?>
</div>

<?=$list->render_pager()?>