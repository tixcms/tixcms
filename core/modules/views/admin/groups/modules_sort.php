<?php if( $groups ):?>
    <div class="tabbable tabs-left">
      <ul class="nav nav-tabs groups-tabs" style="overflow-y: none;">
            <?php $i=0; foreach($groups as $group):?>
                <li<?=$i==0 ? ' class="active"' : ''?> data-id="<?=$group->id?>" data-alias="<?=$group->alias?>">
                    <a href="#group<?=$group->id?>"  data-toggle="tab">
                        <?=$group->name?>
                    </a>
                </li>
            <?php $i++; endforeach?>
        </ul>
      <div class="tab-content">
        <?php $i=0; foreach($groups as $group):?>
            <div class="tab-pane<?=$i==0 ? ' active' : ''?>" id="group<?=$group->id?>" style="width: 20%;">
              
              <ul class="nav nav-tabs nav-stacked modules-list" data-group-id="<?=$group->id?>">
              <?php if( isset($modules_by_groups[$group->alias]) ):?>
                
                <?php foreach($modules_by_groups[$group->alias] as $module):?>
                    <li data-id="<?=$module->id?>">
                        <a href="#">
                            <?=$module->name?>
                            <?=$module->is_menu
                                ? '<span class="label label-success menu-toggle">в меню</span>'
                                : '<span class="label menu-toggle">не в меню</span>'
                            ?>
                        </a>
                    </li>
                <?php endforeach?>
                <?php endif?>
              </ul>              
            </div>
        <?php $i++; endforeach?>
      </div>
    </div>
<?php else:?>
    <p>Нет групп</p>
<?php endif?>

<script>
    var groupFromId = false;
    
    $(document).on('click', ".menu-toggle", function(){
        var id = $(this).parent().parent().data('id');
        $(this).hasClass('label-success') 
            ? $(this).removeClass('label-success')
            : $(this).addClass('label-success');
        
        $.getJSON(BASE_URL + 'admin/modules/is_menu_toggle/' + id, function(data){
            $.pnotify(data);
        });
    });

    $(".modules-list").sortable({
        items: 'li',
        update: function(event, ui){
            var ids = new Array;
            var groupId = groupFromId ? groupFromId : ui.item.parent().data('group-id');
            groupFromId = false;

            $("[data-group-id=" + groupId + "].modules-list li").each(function(i, item){
                ids[i] = ($(this).data("id"));
            });
            
            $.post(BASE_URL + 'admin/modules/reorder', 
                {ids: ids, group_id: groupId},
                function(data){}
            );
        },
    }).disableSelection();
    
    $(".groups-tabs li").droppable({
        drop: function(event, ui){
            var module = $(".ui-sortable-helper");
            groupFromId = module.parent().data('group-id');
            var group = $(this);
            module.removeClass('ui-sortable-helper').css({'position':'', 'left': '', 'right': '', 'z-index':''});
            var moduleHtml = $(module).clone().wrap('<p>').parent().html();
            $(".modules-list[data-group-id=" + group.data('id') + "]").append(moduleHtml);
            module.remove();
            
            $.get(BASE_URL + 'admin/modules/groups/move_module/' + module.data('id') + '/' + group.data('alias'));
            groupFromId = false;
            $.pnotify({
                type: 'success',
                text: 'Изменения сохранены'
            });
        } 
    });
</script>