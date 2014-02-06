<?php if( $table->ajax ):?>
    <script>
        var tableData;
        $(function(){
            tableData = '<?=json_encode($table->get_json_data())?>';
        });
    </script>
<?php endif?>

<?php if( ($table->show_table_with_no_items OR $table->total > 0) AND ($table->search === true OR $table->filters) ):?>

    <div class="row-fluid" style="min-height: 35px;">
        <div class="span7 filters">
            <?php if( $table->filters ):?>
                <?=$table->render_filters()?>
            <?php endif?>
        </div>
        <div class="span5">
            <?php if($table->search === true):?>
            <form class="form-inline search-form" method="get" style="text-align: right; margin-bottom: 5px;">
                <div class="input-append" style="display: block; text-align: right;">
                    <input type="text"
                           name="search"
                           value="<?=$table->url_query->get('search')?>"
                           class="span8"
                           placeholder="Поиск"
                    />
                    
                    <?php if( $table->ajax ):?>
                    
                        <span class="add-on" style="cursor: pointer;" onclick="formSearch(''); return false;" title="очистить">
                            <i class="fa fa-search"></i>
                        </span>
                        
                    <?php else:?>
                    
                        <?=$this->url->anchor(
                            $table->current_url,
                            '<i class="fa fa-search"></i>',
                            array(
                                'class'=>'add-on',
                                'style'=>'cursor: pointer;',
                                'title'=>'сбросить'
                            )
                        )?>
                        
                    <?php endif?>
                </div>
            </form>
            <?php endif?>
        </div>
    </div>
    
<?php endif?>

<?php if( $table->show_table_with_no_items OR $table->total > 0 OR $this->input->is_ajax_request() ):?>

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
    
    <?php if( $table->per_page OR $table->show_total_counter ):?>
    
        <div class="row-fluid">
            <div class="span6">
            
                <?php if( $table->mass_actions_view ):?>
                    <i class="fa fa-long-arrow-right" style="padding-right: 5px; padding-left: 5px;"></i>
                
                    <?=$table->render_mass_actions()?>
                <?php endif?>                
            </div>
            <div class="span6 table-pagination" style="text-align: right;">
            
                <div class="per-page-options" style="display: inline-block; vertical-align: top;">
                    <?=$table->render_per_page_options()?>
                </div>
            
                <?=$table->render_pager()?>
                
                <?php if( $table->show_total_counter ):?>
                    <div style="text-align: right;">
                        Всего записей: <strong class="table-total"><?=$table->total?></strong>
                    </div>
                <?php endif?>
            </div>
        </div>
        
    <?php endif?>
    
<?php else:?>
    <p>
        <?=$table->no_items?>
    </p>
<?php endif?>