<form class="form-inline">
    <input type="text" name="name" value="" placeholder="Имя группы" />
    <input type="text" name="alias" value="" placeholder="Идентификатор" />
    <input type="submit" name="submit" value="Создать группу" class="btn btn-primary" />
</form>

<ul class="groups-list">
    <?php if( $groups ):?>
        <?php foreach($groups as $group):?>
            <li data-id="<?=$group->id?>">
                <?=$group->name?>
                <i class="icon-trash delete-group"></i>
            </li>
        <?php endforeach?>  
    <?php endif?>
</ul>

<?php if( !$groups ):?>
    <p class="no-items">Нет групп</p>
<?php endif?>

<script>
    $(function(){
        $("[name=submit]").click(function(){
            var name = $("[name=name]").val();
            var alias = $("[name=alias]").val();
            if( name && alias ){
                $(".groups-list").append('<li data-id="new">' + name + ' <i class="icon-trash delete-group"></i></li>');
                $(".no-items").remove();
                $("[name=name]").val('');
                $("[name=alias]").val('');
                $.pnotify({
                    type: 'success',
                    text: 'Группа создана'
                });
                $.post(BASE_URL + 'admin/modules/groups/add', {name: name, alias: alias}, function(data){
                    data = $.parseJSON(data);
                    $("[data-id=new]:first").attr('data-id', data.id);
                });
            }
            return false; 
        });
        
        $(document).on('click', ".delete-group", function(){
            var item = $(this).parent();
            $.get(BASE_URL + 'admin/modules/groups/delete/' + item.data('id'));
            item.remove();
            $.pnotify({
                type: 'success',
                text: 'Группа удалена'
            });
        });
        
        $(".groups-list").sortable({
            items: 'li',
            update: function(event, ui){
                var ids = new Array;
    
                $(".groups-list li").each(function(i, item){
                    ids[i] = ($(this).data("id"));
                });
                
                $.post(BASE_URL + 'admin/modules/groups/reorder', 
                    {ids: ids},
                    function(data){
                        data = $.parseJSON(data);
                        $.pnotify(data);
                    }
                );
            },
        });
    });    
</script>

<style>
    .groups-list li {
        cursor: pointer;
    }
    .delete-group {
        cursor: pointer;
    }
</style>