$(function(){
     $(".area-item").hover(function(){
        $(".header-actions", this).removeClass('hide');
     }, function(){
        $(".header-actions", this).addClass('hide');
     });
     
     $(".sortable").sortable({
        handle: '.sortable-helper',
        connectWith: '.sortable',
        items: '.item',
        //placeholder: "ui-state-highlight",
        update: function(event, ui){
            var ids = new Array;
            
            var area = $(this).parent().find(".sortable").attr("id");

            $(this).parent().find("tr.item").each(function(i, item){
                ids[i] = ($(this).data("id")); 
            });
            
            $.post(BASE_URL + 'admin/block/update_blocks_order', 
                {ids: ids, area: area},
                function(data){}
            );
        },
    });
    
    $(".blocks-items li a").popover({
        trigger: 'hover',
        html: 'test',
        placement: 'left'
    });
});