<?=$this->alert->message(
    'info', 
    'Настройка панели управления. '. $this->url->anchor('admin/dashboard', 'Вернуться обратно'), 
    false
)?>

<?php if( $groups ):?>
    <?php if( isset($modules_by_groups['no_group']) ):?>
        <div class="row-fluid">
            <ul class="thumbnails dashboard-items-list" style="margin-bottom: 10px;" data-id="0">
                <?php foreach($modules_by_groups['no_group'] as $item):?>
                    <li class="span1" style="position: relative;" data-id="<?=$item->id?>">
                        <?php if( Notices::has($item->url) ):?>
                            <span 
                                class="badge" 
                                style="position: absolute; top: -10px; right: -10px;" 
                                rel="tooltip"
                                data-title="<?=Notices::get_label($item->url)?>"
                            >
                                <?=Notices::get_count($item->url)?>
                            </span>
                        <?php endif?>
                        <?=$this->url->anchor(
                            'admin/'. $item->url,
                            $this->assets->img($item->url .'::icon.png', array(
                                'style'=>'width: 50px; height: 50px;'
                            )),
                            array(
                                'class'=>'thumbnail dashboard-item'. ( $item->is_menu ? ' is-menu' : ' not-is-menu' ),
                                'rel'=>'tooltip',
                                'data-title'=>$item->name,
                                'data-placement'=>'bottom',
                                //'style'=>'height: 89px;'
                            )
                        )?>
                    </li> 
                <?php endforeach?>
            </ul>
        </div>
    <?php unset($modules_by_groups['no_group']); endif?>

    <div class="groups-list">
    
    <?php foreach($groups as $group):?>  
        <?php if( isset($modules_by_groups[$group->alias]) ):?>    
        
            <div class="groups-list-item" data-id="<?=$group->id?>">
        
                <h3>
                    <i class="fa fa-arrows-v sort-handle" style="font-size: 22px; cursor: pointer;"></i> <?=$group->name?>
                </h3>
                
                <div class="row-fluid">
                    <ul class="thumbnails dashboard-items-list" style="margin-bottom: 10px;" data-id="<?=$group->id?>">
                        <?php foreach($modules_by_groups[$group->alias] as $item):?>
                            <li class="span1" style="position: relative;" data-id="<?=$item->id?>">
                                <?php if( Notices::has($item->url) ):?>
                                    <span 
                                        class="badge" 
                                        style="position: absolute; top: -10px; right: -10px;" 
                                        rel="tooltip"
                                        data-title="<?=Notices::get_label($item->url)?>"
                                    >
                                        <?=Notices::get_count($item->url)?>
                                    </span>
                                <?php endif?>
                                <?=$this->url->anchor(
                                    'admin/'. $item->url,
                                    $this->assets->img($item->url .'::icon.png', array(
                                        'style'=>'width: 50px; height: 50px;'
                                    )),
                                    array(
                                        'class'=>'thumbnail dashboard-item'. ( $item->is_menu ? ' is-menu' : ' not-is-menu' ),
                                        'rel'=>'tooltip',
                                        'data-title'=>$item->name,
                                        'data-placement'=>'bottom',
                                        'data-id'=>$item->id,
                                        //'style'=>'height: 89px;'
                                    )
                                )?>
                            </li> 
                        <?php endforeach?>
                    </ul>
                </div>
            
            </div>
        <?php endif?>
    <?php endforeach?>  
    
    </div>
<?php endif?>

<style>
    .not-is-menu {
        opacity: 0.3;
    }
</style>

<script>
    var isMenuUrl = '<?=$this->url->site_url('admin/modules/is_menu_toggle')?>';
    var groupSortUrl = '<?=$this->url->site_url('admin/modules/groups/reorder')?>';
    var itemsSortUrl = '<?=$this->url->site_url('admin/modules/reorder')?>';

    $(function(){
         $(".dashboard-item").click(function(){
            var id = $(this).data('id');
            
            if( $(this).hasClass('is-menu') ){
                $(this).removeClass('is-menu').addClass('not-is-menu');
            } else {
                $(this).removeClass('not-is-menu').addClass('is-menu');
            }
            
            $.get(isMenuUrl + id);
            
            return false;
         });
         
         $(".groups-list").sortable({
            items: '.groups-list-item',
            handle: '.sort-handle',
            update: function(event, ui){
                var ids = new Array;
    
                $(".groups-list-item").each(function(i, item){
                    ids[i] = ($(this).data("id"));
                });
                
                $.post(groupSortUrl, 
                    {ids: ids},
                    function(data){
                        data = $.parseJSON(data);
                        $.pnotify(data);
                    }
                );
            },
        });
        
        $(".dashboard-items-list").sortable({
            items: 'li',
            connectWith: '.dashboard-items-list',
            update: function(event, ui){
                var ids = new Array;
                var groupId = ui.item.parent().data('id');
    
                $("[data-id=" + groupId + "] li").each(function(i, item){
                    ids[i] = ($(this).data("id"));
                });
                
                $.post(itemsSortUrl,
                    {ids: ids, group_id: groupId},
                    function(data){}
                );
            },
        }).disableSelection();
    });
</script>