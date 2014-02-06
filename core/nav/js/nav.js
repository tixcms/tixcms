var currentEl;

$(function(){  
    $(".nav-pills a").click(function(){
        var areaAlias = ($(this).attr('href')).replace('#', ''); 
        
        $("[name=area_alias]").val(areaAlias);
        
        $("[name=parent_id] option").hide();
        $("[name=parent_id] option.area-" + areaAlias).show();
    });
    
    $(".type").change(function(){
        currentEl = $(".input_url_" + $(this).val());  
              
        $(".input_url").hide();
        currentEl.show();

        if( $(this).val() != 'text' ){
            $(".input_url_text").parent().hide();
            $("[name=url]").val(currentEl.val());
        } else {
            $(".input_url_text").parent().show();
        }
    });
    
    $(".input_url").change(function(){
        var value = $('option:selected', this).val();
        $("[name=url]").val(value);
    });
    
    $("[name=area_alias]").change(function(){
         var alias = $('option:selected', this).val();
         $(".parent_id").hide();
         $(".parent_id_" + alias).show();
         $("[name=parent_id]").val(0);
    });
    
    $(".parent_id").change(function(){
        var value = $('option:selected', this).val();
        $("[name=parent_id]").val(value);
    });
    
    $(".sortable").sortable({
        placeholder: "sortable-placeholder",
        forcePlaceholderSize: true,
        handle: '.sortable-helper',
        items: ' > .item',
        update: function(event, ui){
            var linkId = ui.item.data('id');
            var parent = ui.item.parents('li');
            var parentId = parent.data('id');
            
            var ids = new Array;
            $(" > li", ui.item.parents('ul')).each(function(i, event){
                ids[i] = $(this).data('id');
            });
            
            $.post(BASE_URL + 'admin/nav/position/' + linkId + '/' + parentId, 
                {link_id: linkId, parent_id: parentId, ids: ids}, 
                function(data){
                    data = $.parseJSON(data);
                    $.pnotify(data);
                }
            );
        },
    });
});